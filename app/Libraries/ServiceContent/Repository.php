<?php

namespace App\Libraries\ServiceContent;

use RuntimeException;
use CodeIgniter\Database\BaseConnection;

class Repository
{
    public function __construct(public ?BaseConnection $db = null)
    {
        $this->db ??= db_connect();
    }

    public function table(string $suffix)
    {
        return $this->db->table('service_content_' . $suffix);
    }

    public function ready(): bool
    {
        foreach (['contexts', 'families', 'pages', 'revisions', 'batches', 'jobs', 'audit', 'redirects', 'budgets'] as $suffix) {
            if (! $this->db->tableExists('service_content_' . $suffix)) return false;
        }
        return true;
    }

    public function context(): ?array
    {
        return $this->table('contexts')->orderBy('id', 'desc')->get(1)->getRowArray();
    }

    public function suggestContext(string $key, int $actor): int
    {
        if (! preg_match('/^[a-f0-9]{32,64}$/', $key)) throw new RuntimeException('Solicitação inválida.');
        if (! $this->db->tableExists('service_content_context_drafts')) throw new RuntimeException('Atualize a instalação do módulo.');
        $existing = $this->table('batches')->where('request_key', $key)->get()->getRowArray();
        if ($existing) return (int) $existing['id'];
        $families = $this->table('families')->orderBy('name')->get()->getResultArray();
        $seed = 'Nome do negócio: James Webb Studio. Serviços cadastrados pelo estúdio: ' . implode('; ', array_column($families, 'name')) . '. ';
        $seed .= $this->context()['facts'] ?? 'Preços, prazos, quantidade de fotos, equipamentos e regiões não foram confirmados. Quando não houver informação, apresentar como condição a combinar no briefing, sem inventar dados. Produzir uma base editorial reutilizável para todos os serviços cadastrados.';
        $this->db->transException(true)->transBegin();
        try {
            $this->table('batches')->insert(['request_key' => $key, 'context_id' => 0, 'created_by' => $actor, 'token_limit' => (config('ServicePages')->maxInputTokens + config('ServicePages')->maxOutputTokens) * config('ServicePages')->maxAttempts, 'created_at' => date('Y-m-d H:i:s')]);
            $batch = (int) $this->db->insertID();
            $this->table('context_drafts')->insert(['batch_id' => $batch, 'seed_facts' => $seed]);
            $this->table('jobs')->insert(['batch_id' => $batch, 'page_id' => 0, 'base_version' => 0, 'created_at' => date('Y-m-d H:i:s')]);
            $this->audit(0, 'context_suggestion_queued', $actor);
            $this->db->transCommit();
            return $batch;
        } catch (\Throwable $error) { $this->db->transRollback(); throw $error; }
    }

    public function audit(int $page, string $action, int $actor, int $revision = 0): void
    {
        $this->table('audit')->insert(['page_id' => $page, 'actor_id' => $actor, 'action' => $action, 'revision_id' => $revision, 'created_at' => date('Y-m-d H:i:s')]);
    }

    public function saveContext(string $facts, string $whatsapp, int $actor): void
    {
        $facts = Content::text($facts, 10000, 'fatos do estúdio');
        Content::whatsapp($whatsapp, 'Contato', '');
        $this->table('contexts')->insert(['facts' => $facts, 'whatsapp' => $whatsapp, 'created_by' => $actor, 'created_at' => date('Y-m-d H:i:s')]);
        $this->audit(0, 'context_created', $actor);
    }

    public function createFamily(string $name, string $description, int $actor): void
    {
        $name = Content::text($name, 150, 'família');
        $description = Content::text($description, 2500, 'descrição');
        $slug = Content::slug($name);
        if ($this->table('families')->where('slug', $slug)->countAllResults()) throw new RuntimeException('Família já cadastrada.');
        $this->table('families')->insert(['name' => $name, 'slug' => $slug, 'description' => $description, 'created_at' => date('Y-m-d H:i:s')]);
        $this->audit(0, 'family_created', $actor);
    }

    public function family(int $id): array
    {
        return $this->table('families')->where('id', $id)->get()->getRowArray() ?? throw new RuntimeException('Família não encontrada.');
    }

    public function page(int $id): array
    {
        return $this->table('pages')->where('id', $id)->get()->getRowArray() ?? throw new RuntimeException('Página não encontrada.');
    }

    public function revision(int $id): ?array
    {
        if (! $id) return null;
        $revision = $this->table('revisions')->where('id', $id)->get()->getRowArray();
        if ($revision) $revision['data'] = json_decode($revision['content'], true, 32, JSON_THROW_ON_ERROR);
        return $revision;
    }

    public function createPage(int $family, string $topic, int $actor): int
    {
        $this->family($family);
        $topic = Content::text($topic, 200, 'tema');
        $slug = Content::slug($topic);
        $path = 'servicos/' . $this->family($family)['slug'] . '/' . $slug;
        if ($this->table('pages')->where(['family_id' => $family, 'slug' => $slug])->countAllResults()
            || $this->table('redirects')->where('old_path', $path)->countAllResults()) throw new RuntimeException('Tema/endereço já cadastrado.');
        $now = date('Y-m-d H:i:s');
        $this->table('pages')->insert(['family_id' => $family, 'topic' => $topic, 'slug' => $slug, 'created_by' => $actor, 'created_at' => $now, 'updated_at' => $now]);
        $id = (int) $this->db->insertID();
        $this->audit($id, 'created', $actor);
        return $id;
    }

    public function photos(array $ids): array
    {
        if (! $ids) return [];
        $photos = $this->db->table('photos')->select('photos.*, heroes.name AS hero_name')->join('heroes', 'heroes.id = photos.hero_id')->where('heroes.published', 1)->whereIn('photos.id', $ids)->get()->getResultArray();
        if (count($photos) !== count(array_unique($ids))) throw new RuntimeException('Selecione apenas fotos de ensaios publicados e com autorização de uso.');
        foreach ($photos as $photo) {
            $root = realpath(FCPATH . 'uploads/heroes');
            $file = realpath(FCPATH . $photo['image_path']);
            if (! $root || ! $file || ! str_starts_with(strtolower($file), strtolower($root . DIRECTORY_SEPARATOR))) throw new RuntimeException('Fotografia pública indisponível.');
        }
        return $photos;
    }

    public function addRevision(int $page, int $context, array $content, int $actor, string $source = 'manual', string $model = ''): int
    {
        $content = Content::validate($content);
        $this->photos($content['photo_ids']);
        $this->table('revisions')->insert(['page_id' => $page, 'context_id' => $context, 'content' => Content::encode($content), 'source' => $source, 'model' => $model, 'created_by' => $actor, 'created_at' => date('Y-m-d H:i:s')]);
        return (int) $this->db->insertID();
    }

    public function edit(int $id, int $version, array $content, int $actor, ?int $revisionToRestore = null): void
    {
        $context = $this->context() ?? throw new RuntimeException('Cadastre os fatos do estúdio e WhatsApp primeiro.');
        $this->db->transException(true)->transBegin();
        try {
            if ($revisionToRestore) {
                $restore = $this->revision($revisionToRestore);
                if (! $restore || (int) $restore['page_id'] !== $id) throw new RuntimeException('Revisão inválida.');
                $content = $restore['data'];
            }
            $revision = $this->addRevision($id, (int) $context['id'], $content, $actor);
            $this->table('pages')->where(['id' => $id, 'lock_version' => $version])->set(['current_revision_id' => $revision, 'updated_at' => date('Y-m-d H:i:s')])->set('lock_version', 'lock_version + 1', false)->update();
            if ($this->db->affectedRows() !== 1) throw new RuntimeException('A página mudou. Recarregue e compare as revisões antes de salvar.');
            $this->audit($id, $revisionToRestore ? 'restored_to_draft' : 'edited', $actor, $revision);
            $this->db->transCommit();
        } catch (\Throwable $error) { $this->db->transRollback(); throw $error; }
    }

    public function publish(int $id, int $version, int $actor): void
    {
        $page = $this->page($id);
        $revision = $this->revision((int) $page['current_revision_id']) ?? throw new RuntimeException('Salve e revise o conteúdo primeiro.');
        Content::validate($revision['data']);
        $this->photos($revision['data']['photo_ids']);
        $context = $this->table('contexts')->where('id', $revision['context_id'])->get()->getRowArray();
        Content::whatsapp($context['whatsapp'] ?? '', '', '');
        if ($revision['data']['fact_warnings']) throw new RuntimeException('Resolva os fatos pendentes no editor antes de publicar.');
        $this->db->transException(true)->transBegin();
        try {
            $this->table('pages')->where(['id' => $id, 'lock_version' => $version])->set(['status' => 'published', 'ever_published' => 1, 'published_revision_id' => $revision['id'], 'published_at' => date('Y-m-d H:i:s')])->set('lock_version', 'lock_version + 1', false)->update();
            if ($this->db->affectedRows() !== 1) throw new RuntimeException('Conteúdo mudou; revise novamente.');
            $this->audit($id, 'published', $actor, (int) $revision['id']);
            $this->db->transCommit();
        } catch (\Throwable $error) { $this->db->transRollback(); throw $error; }
    }

    public function archive(int $id, int $version, int $actor): void
    {
        $this->db->transException(true)->transBegin();
        try {
            $this->table('pages')->where(['id' => $id, 'lock_version' => $version])->set('status', 'archived')->set('lock_version', 'lock_version + 1', false)->update();
            if ($this->db->affectedRows() !== 1) throw new RuntimeException('Página mudou; recarregue.');
            $this->audit($id, 'archived', $actor);
            $this->db->transCommit();
        } catch (\Throwable $error) { $this->db->transRollback(); throw $error; }
    }

    public function path(array $page): string
    {
        return 'servicos/' . ($page['family_slug'] ?? $this->family((int) $page['family_id'])['slug']) . '/' . $page['slug'];
    }

    public function rename(int $id, int $version, string $slug, int $actor): void
    {
        $page = $this->page($id);
        $slug = Content::slug($slug);
        $newPath = 'servicos/' . $this->family((int) $page['family_id'])['slug'] . '/' . $slug;
        if ($slug === $page['slug']) return;
        if ($this->table('pages')->where(['family_id' => $page['family_id'], 'slug' => $slug])->countAllResults()) throw new RuntimeException('Endereço já utilizado.');
        $alias = $this->table('redirects')->where('old_path', $newPath)->get()->getRowArray();
        if ($alias && (int) $alias['page_id'] !== $id) throw new RuntimeException('Endereço reservado por outra página.');
        $this->db->transException(true)->transBegin();
        try {
            $this->table('pages')->where(['id' => $id, 'lock_version' => $version])->set('slug', $slug)->set('lock_version', 'lock_version + 1', false)->update();
            if ($this->db->affectedRows() !== 1) throw new RuntimeException('Página mudou; recarregue.');
            $this->table('redirects')->where(['old_path' => $newPath, 'page_id' => $id])->delete();
            if ($page['ever_published']) $this->table('redirects')->insert(['old_path' => $this->path($page), 'page_id' => $id]);
            $this->audit($id, 'slug_changed', $actor);
            $this->db->transCommit();
        } catch (\Throwable $error) { $this->db->transRollback(); throw $error; }
    }

    public function publicPages(?int $family = null): array
    {
        $query = $this->db->table('service_content_pages p')->select('p.*, f.slug AS family_slug, r.content AS public_content')
            ->join('service_content_families f', 'f.id = p.family_id')->join('service_content_revisions r', 'r.id = p.published_revision_id')
            ->where('p.status', 'published');
        if ($family) $query->where('p.family_id', $family);
        $pages = $query->orderBy('p.id', 'desc')->get()->getResultArray();
        foreach ($pages as &$page) $page['data'] = json_decode($page['public_content'], true, 32, JSON_THROW_ON_ERROR);
        unset($page);
        return $pages;
    }

    public function batch(int $family, array $topics, string $key, int $actor, bool $generate, ?int $pageId = null): int
    {
        if (! preg_match('/^[a-f0-9]{32,64}$/', $key)) throw new RuntimeException('Identificador do lote inválido.');
        $old = $this->table('batches')->where('request_key', $key)->get()->getRowArray();
        if ($old) return (int) $old['id'];
        $context = $this->context() ?? throw new RuntimeException('Cadastre os fatos primeiro.');
        $topics = array_values(array_filter(array_map('trim', $topics)));
        if (! $topics || count($topics) > config('ServicePages')->maxBatch) throw new RuntimeException('Informe de 1 a 25 temas.');
        $unique = [];
        foreach ($topics as $topic) $unique[Content::slug($topic)] = Content::text($topic, 200, 'tema');
        $reserve = config('ServicePages')->maxInputTokens + config('ServicePages')->maxOutputTokens;
        $this->db->transException(true)->transBegin();
        try {
            $this->table('batches')->insert(['request_key' => $key, 'context_id' => $context['id'], 'created_by' => $actor, 'token_limit' => count($unique) * $reserve * config('ServicePages')->maxAttempts, 'created_at' => date('Y-m-d H:i:s'), 'status' => $generate ? 'queued' : 'completed']);
            $batch = (int) $this->db->insertID();
            $queued = 0;
            foreach ($unique as $slug => $topic) {
                if ($pageId) $page = $this->page($pageId);
                else {
                    $existing = $this->table('pages')->where(['family_id' => $family, 'slug' => $slug])->get()->getRowArray();
                    if ($existing) continue; // Never overwrite an existing page from a bulk list.
                    $page = $this->page($this->createPage($family, $topic, $actor));
                }
                if ($generate) {
                    $this->table('jobs')->insert(['batch_id' => $batch, 'page_id' => $page['id'], 'base_version' => $page['lock_version'], 'created_at' => date('Y-m-d H:i:s')]);
                    $queued++;
                }
            }
            if ($generate && $queued === 0) $this->table('batches')->where('id', $batch)->update(['status' => 'completed']);
            $this->audit($pageId ?? 0, $generate ? 'generation_queued' : 'batch_drafts_created', $actor);
            $this->db->transCommit();
            return $batch;
        } catch (\Throwable $error) { $this->db->transRollback(); throw $error; }
    }
}
