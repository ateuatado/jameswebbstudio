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
            'threads_post_url' => trim((string) $this->request->getPost('threads_post_url')),
            'original_text' => trim((string) $this->request->getPost('original_text')),
            'context_category' => trim((string) $this->request->getPost('context_category')),
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
            'is_page_active' => (int) ($this->request->getPost('is_page_active') ?? 0),
            'next_action_at' => trim((string) $this->request->getPost('next_action_at')) ?: null,
            'response_notes' => trim((string) $this->request->getPost('response_notes')) ?: null,
            'proposal_value_cents' => $money,
            'closed_reason' => trim((string) $this->request->getPost('closed_reason')) ?: null,
        ];
    }

    private function formData(?object $opportunity): array
    {
        return ['title' => $opportunity ? 'Editar oportunidade' : 'Nova oportunidade', 'opportunity' => $opportunity, 'statuses' => ThreadsOpportunityModel::STATUSES, 'categories' => ThreadsOpportunityModel::CATEGORIES, 'adminUsers' => $this->adminUsers()];
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
