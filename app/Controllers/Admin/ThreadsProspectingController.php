<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ThreadsOpportunityModel;

class ThreadsProspectingController extends BaseController
{
    public function index()
    {
        $model = new ThreadsOpportunityModel();
        $status = trim((string) ($this->request->getGet('status') ?? ''));
        $assigned = (int) ($this->request->getGet('assigned_user_id') ?? 0);

        $query = $model->withAssignedUser()->orderBy('threads_opportunities.created_at', 'DESC');
        if ($status && isset(ThreadsOpportunityModel::STATUSES[$status])) $query->where('threads_opportunities.status', $status);
        if ($assigned) $query->where('threads_opportunities.assigned_user_id', $assigned);

        $opportunities = $query->findAll();
        foreach ($opportunities as $opportunity) $opportunity->page_views = $model->pageViews((int) $opportunity->id);

        $counts = [];
        foreach (ThreadsOpportunityModel::STATUSES as $key => $label) {
            $counts[$key] = $model->where('status', $key)->countAllResults();
        }

        return view('admin/threads/index', [
            'title' => 'Prospecção no Threads', 'opportunities' => $opportunities,
            'statuses' => ThreadsOpportunityModel::STATUSES, 'categories' => ThreadsOpportunityModel::CATEGORIES,
            'counts' => $counts, 'selectedStatus' => $status, 'selectedAssigned' => $assigned,
            'adminUsers' => $this->adminUsers(),
        ]);
    }

    public function new()
    {
        return view('admin/threads/form', $this->formData(null));
    }

    public function create()
    {
        $model = new ThreadsOpportunityModel();
        $data = $this->formPayload();
        $data['page_token'] = bin2hex(random_bytes(32));
        $data['assigned_user_id'] = (int) ($data['assigned_user_id'] ?: auth()->id());
        $data['last_action_at'] = date('Y-m-d H:i:s');
        $data = $this->applyGeneratedDefaults($data);

        if (!$model->insert($data)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $model->errors()));
        }
        $id = (int) $model->getInsertID();
        $this->event($id, 'created');
        return redirect()->to(site_url('admin/threads/' . $id . '/edit'))->with('message', 'Oportunidade criada. Revise os textos antes de usar.');
    }

    public function edit(int $id)
    {
        $model = new ThreadsOpportunityModel();
        $opportunity = $model->find($id);
        if (!$opportunity) return redirect()->to(site_url('admin/threads'))->with('error', 'Oportunidade não encontrada.');
        return view('admin/threads/form', $this->formData($opportunity));
    }

    public function update(int $id)
    {
        $model = new ThreadsOpportunityModel();
        $existing = $model->find($id);
        if (!$existing) return redirect()->to(site_url('admin/threads'))->with('error', 'Oportunidade não encontrada.');
        $data = $this->formPayload();
        $data['page_token'] = $existing->page_token;
        $data['last_action_at'] = date('Y-m-d H:i:s');
        $data = $this->applyGeneratedDefaults($data, $existing->page_token);
        if (!$model->update($id, $data)) return redirect()->back()->withInput()->with('error', implode('<br>', $model->errors()));
        $this->event($id, 'updated');
        return redirect()->to(site_url('admin/threads/' . $id . '/edit'))->with('message', 'Oportunidade atualizada.');
    }

    public function status(int $id)
    {
        $model = new ThreadsOpportunityModel();
        $opportunity = $model->find($id);
        $newStatus = (string) $this->request->getPost('status');
        if (!$opportunity || !isset(ThreadsOpportunityModel::STATUSES[$newStatus])) return redirect()->back()->with('error', 'Status inválido.');
        $model->update($id, ['status' => $newStatus, 'last_action_at' => date('Y-m-d H:i:s')]);
        $this->event($id, 'status_' . $newStatus);
        return redirect()->back()->with('message', 'Status atualizado.');
    }

    public function regenerate(int $id)
    {
        $model = new ThreadsOpportunityModel();
        if (!$model->find($id)) return redirect()->back()->with('error', 'Oportunidade não encontrada.');
        $model->update($id, ['page_token' => bin2hex(random_bytes(32)), 'is_page_active' => 1]);
        $this->event($id, 'token_regenerated');
        return redirect()->back()->with('message', 'Link regenerado. O link anterior foi invalidado.');
    }

    private function formPayload(): array
    {
        $money = trim((string) ($this->request->getPost('proposal_value') ?? ''));
        $money = $money !== '' ? (int) round((float) str_replace(',', '.', preg_replace('/[^0-9,.-]/', '', $money)) * 100) : null;
        return [
            'assigned_user_id' => (int) ($this->request->getPost('assigned_user_id') ?? 0) ?: null,
            'threads_username' => trim((string) $this->request->getPost('threads_username')),
            'threads_post_url' => trim((string) $this->request->getPost('threads_post_url')) ?: null,
            'original_text' => trim((string) $this->request->getPost('original_text')),
            'context_category' => trim((string) $this->request->getPost('context_category')) ?: 'outro',
            'priority' => $this->request->getPost('priority') ?: 'medium',
            'city' => trim((string) $this->request->getPost('city')) ?: null,
            'status' => $this->request->getPost('status') ?: 'identified',
            'comment_copy' => trim((string) $this->request->getPost('comment_copy')) ?: null,
            'direct_copy' => trim((string) $this->request->getPost('direct_copy')) ?: null,
            'page_title' => trim((string) $this->request->getPost('page_title')) ?: null,
            'page_intro' => trim((string) $this->request->getPost('page_intro')) ?: null,
            'offer_copy' => trim((string) $this->request->getPost('offer_copy')) ?: null,
            'cta_label' => trim((string) $this->request->getPost('cta_label')) ?: 'Quero conversar',
            'cta_url' => trim((string) $this->request->getPost('cta_url')) ?: null,
            'whatsapp_owner' => trim((string) $this->request->getPost('whatsapp_owner')) ?: null,
            'whatsapp_number' => trim((string) $this->request->getPost('whatsapp_number')) ?: null,
            'is_page_active' => (int) ($this->request->getPost('is_page_active') ?? 1),
            'next_action_at' => trim((string) $this->request->getPost('next_action_at')) ?: null,
            'response_notes' => trim((string) $this->request->getPost('response_notes')) ?: null,
            'proposal_value_cents' => $money,
            'closed_reason' => trim((string) $this->request->getPost('closed_reason')) ?: null,
        ];
    }

    private function formData(?object $opportunity): array
    {
        $settings = (new \App\Models\StudioSettingModel())->getAll();
        return ['title' => $opportunity ? 'Editar oportunidade' : 'Nova oportunidade', 'opportunity' => $opportunity, 'statuses' => ThreadsOpportunityModel::STATUSES, 'categories' => ThreadsOpportunityModel::CATEGORIES, 'adminUsers' => $this->adminUsers(), 'whatsappContacts' => ['marco' => ['label' => 'Meu WhatsApp', 'number' => $settings['studio_phone'] ?? ''], 'wife' => ['label' => 'WhatsApp da minha esposa', 'number' => $settings['studio_whatsapp_wife'] ?? '']]];
    }

    private function applyGeneratedDefaults(array $data, ?string $token = null): array
    {
        $username = ltrim(trim((string) ($data['threads_username'] ?? 'perfil')), '@');
        $handle = '@' . $username;
        $postUrl = $data['threads_post_url'] ?? null;
        if (!$postUrl) $data['threads_post_url'] = 'https://www.threads.com/@' . rawurlencode($username);
        $link = site_url('convite/' . ($token ?: $data['page_token']));
        $category = $data['context_category'] ?: 'outro';

        $data['comment_copy'] = $data['comment_copy'] ?: "{$handle}, sua publicação chamou nossa atenção de um jeito muito bonito. Eu e minha esposa, do James Webb Studio, na Lapa (SP), preparamos uma surpresa pensando no que você escreveu e te mandamos no direct. ✨";
        $data['direct_copy'] = $data['direct_copy'] ?: "Oi, {$handle}! Li sua publicação e preparei uma página especialmente a partir dela. Eu e minha esposa atendemos juntos no James Webb Studio, na Lapa (SP), como parte do nosso protocolo de segurança e acolhimento. Deixei o link aqui: {$link}";
        $data['page_title'] = $data['page_title'] ?: 'Uma página preparada para você';
        $data['page_intro'] = $data['page_intro'] ?: "Sua publicação sobre {$category} chamou nossa atenção. Imaginamos como essa presença poderia se transformar em imagens feitas com tempo, direção e cuidado.";
        $data['offer_copy'] = $data['offer_copy'] ?: 'Gostaríamos de te convidar para conhecer uma experiência de ensaio no James Webb Studio, na Lapa (SP) — um encontro para registrar quem você é e o que deseja transmitir, sem pressa e sem obrigação. O atendimento é feito por mim e minha esposa juntos, seguindo o protocolo de segurança e acolhimento do estúdio.';
        $data['cta_label'] = $data['cta_label'] ?: 'Quero conversar';

        $contacts = (new \App\Models\StudioSettingModel())->getAll();
        $owner = $data['whatsapp_owner'] ?: 'marco';
        $number = preg_replace('/\D+/', '', (string) ($data['whatsapp_number'] ?: ($owner === 'wife' ? ($contacts['studio_whatsapp_wife'] ?? '') : ($contacts['studio_phone'] ?? ''))));
        $data['whatsapp_owner'] = $owner;
        $data['whatsapp_number'] = $number ?: null;
        if ($number) {
            $message = rawurlencode("Olá! Vi o convite que vocês prepararam para mim no James Webb Studio.");
            $data['cta_url'] = 'https://wa.me/' . $number . '?text=' . $message;
            $data['cta_label'] = 'Conversar pelo WhatsApp';
        }

        return $data;
    }

    private function adminUsers(): array
    {
        return \Config\Database::connect()->table('users')->select('users.id, users.username, users.display_name')->join('auth_groups_users', 'auth_groups_users.user_id = users.id')->whereIn('auth_groups_users.group', ['admin', 'superadmin'])->groupBy('users.id')->orderBy('users.username')->get()->getResult();
    }

    private function event(int $id, string $type): void
    {
        \Config\Database::connect()->table('threads_opportunity_events')->insert(['opportunity_id' => $id, 'event_type' => $type, 'created_at' => date('Y-m-d H:i:s')]);
    }
}
