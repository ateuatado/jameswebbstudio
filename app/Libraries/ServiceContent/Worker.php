<?php

namespace App\Libraries\ServiceContent;

use RuntimeException;

class Worker
{
    public function __construct(private Repository $repo, private Provider $provider)
    {
    }

    public function once(): bool
    {
        $r = $this->repo;
        $now = date('Y-m-d H:i:s');
        // Expired calls are not replayed automatically: provider may have billed them.
        $r->table('jobs')->where('state', 'running')->where('lease_expires_at <', $now)->update(['state' => 'failed', 'error' => 'Worker interrompido. Consumo desconhecido; revise antes de repetir.']);
        $job = $r->table('jobs')->where('state', 'queued')->groupStart()->where('available_at', null)->orWhere('available_at <=', $now)->groupEnd()->orderBy('id')->get(1)->getRowArray();
        if (! $job) return false;
        $batch = $r->table('batches')->where('id', $job['batch_id'])->get()->getRowArray();
        if (! $batch || $batch['status'] === 'cancelled') {
            $r->table('jobs')->where(['id' => $job['id'], 'state' => 'queued'])->update(['state' => 'cancelled']);
            return true;
        }
        $config = config('ServicePages');
        $reservation = $config->maxInputTokens + $config->maxOutputTokens;
        $costReserve = (int) ceil($config->maxInputTokens * $config->inputUsdPerMillion + $config->maxOutputTokens * $config->outputUsdPerMillion);
        if ($config->pricingModel !== $config->model || $costReserve <= 0 || $config->dailyUsdLimit <= 0) {
            throw new RuntimeException('Confirme as tarifas e o limite financeiro para o modelo selecionado.');
        }
        $day = date('Y-m-d');
        $r->table('budgets')->ignore(true)->insert(['budget_day' => $day, 'reserved_tokens' => 0]);
        $token = bin2hex(random_bytes(16));
        $r->db->transException(true)->transBegin();
        try {
            $r->table('jobs')->where(['id' => $job['id'], 'state' => 'queued'])->set(['state' => 'running', 'lease_token' => $token, 'lease_expires_at' => date('Y-m-d H:i:s', time() + 180)])->set('attempts', 'attempts + 1', false)->update();
            if ($r->db->affectedRows() !== 1) { $r->db->transRollback(); return true; }
            $r->table('batches')->where('id', $batch['id'])->where('status !=', 'cancelled')->where('reserved_tokens <=', (int) $batch['token_limit'] - $reservation)->set('reserved_tokens', 'reserved_tokens + ' . $reservation, false)->update();
            if ($r->db->affectedRows() !== 1) throw new RuntimeException('Lote cancelado ou limite do lote atingido.');
            $r->table('budgets')->where('budget_day', $day)->where('reserved_tokens <=', $config->dailyTokenLimit - $reservation)->where('reserved_microusd <=', (int) floor($config->dailyUsdLimit * 1000000) - $costReserve)->set('reserved_tokens', 'reserved_tokens + ' . $reservation, false)->set('reserved_microusd', 'reserved_microusd + ' . $costReserve, false)->update();
            if ($r->db->affectedRows() !== 1) throw new RuntimeException('Limite diário reservado atingido. Reavalie o orçamento.');
            $r->table('batches')->where('id', $batch['id'])->set('reserved_microusd', 'reserved_microusd + ' . $costReserve, false)->update();
            $r->db->transCommit();
        } catch (\Throwable $error) {
            $r->db->transRollback();
            $r->table('jobs')->where(['id' => $job['id'], 'state' => 'queued'])->update(['state' => 'failed', 'error' => 'Limite de consumo atingido ou lote cancelado.']);
            return true;
        }
        try {
            $page = $r->page((int) $job['page_id']);
            $family = $r->family((int) $page['family_id']);
            $context = $r->table('contexts')->where('id', $batch['context_id'])->get()->getRowArray();
            $result = $this->provider->generate($page['topic'], $family['name'], $context['facts']);
            $r->db->transException(true)->transBegin();
            try {
                // Own the lease before accepting an external result.
                $r->table('jobs')->where(['id' => $job['id'], 'state' => 'running', 'lease_token' => $token])->update(['state' => 'succeeded', 'input_tokens' => max(0, (int) $result['input_tokens']), 'output_tokens' => max(0, (int) $result['output_tokens'])]);
                if ($r->db->affectedRows() !== 1) throw new RuntimeException('Lease encerrado.');
                $revision = $r->addRevision((int) $page['id'], (int) $context['id'], $result['content'], (int) $batch['created_by'], 'ai', $result['model']);
                $r->table('jobs')->where('id', $job['id'])->update(['revision_id' => $revision]);
                $liveBatch = $r->table('batches')->where('id', $batch['id'])->get()->getRowArray();
                if ($liveBatch['status'] !== 'cancelled') {
                    $r->table('pages')->where(['id' => $page['id'], 'lock_version' => $job['base_version']])->set('current_revision_id', $revision)->set('lock_version', 'lock_version + 1', false)->update();
                }
                $r->table('batches')->where('id', $batch['id'])->set('used_tokens', 'used_tokens + ' . (max(0, (int) $result['input_tokens']) + max(0, (int) $result['output_tokens'])), false)->update();
                $estimatedCost = (int) ceil(max(0, (int) $result['input_tokens']) * $config->inputUsdPerMillion + max(0, (int) $result['output_tokens']) * $config->outputUsdPerMillion);
                $r->table('batches')->where('id', $batch['id'])->set('used_microusd', 'used_microusd + ' . $estimatedCost, false)->update();
                $r->audit((int) $page['id'], 'ai_draft_created', (int) $batch['created_by'], $revision);
                $r->db->transCommit();
            } catch (\Throwable $error) { $r->db->transRollback(); throw $error; }
        } catch (\Throwable $error) {
            // Only provider errors created by our adapter may reach the UI.
            $safe = $error instanceof RuntimeException ? $error->getMessage() : 'Falha ao processar o rascunho. Consumo pode ter ocorrido.';
            $r->table('jobs')->where(['id' => $job['id'], 'state' => 'running', 'lease_token' => $token])->update(['state' => 'failed', 'error' => mb_substr($safe, 0, 400)]);
        }
        $pending = $r->table('jobs')->where('batch_id', $batch['id'])->whereIn('state', ['queued', 'running'])->countAllResults();
        if (! $pending) $r->table('batches')->where('id', $batch['id'])->where('status !=', 'cancelled')->update(['status' => 'completed']);
        return true;
    }
}
