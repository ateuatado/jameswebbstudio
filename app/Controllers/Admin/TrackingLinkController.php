<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TrackingHitModel;
use App\Models\TrackingLinkModel;

class TrackingLinkController extends BaseController
{
    // ── Index ──────────────────────────────────────────────────────────────────
    public function index(): string
    {
        $model = new TrackingLinkModel();
        return view('admin/tracking/index', [
            'links' => $model->withHitCount(),
        ]);
    }

    // ── Create / Store ─────────────────────────────────────────────────────────
    public function create(): string
    {
        return view('admin/tracking/form', ['link' => null]);
    }

    public function store()
    {
        $model = new TrackingLinkModel();
        $data  = $this->getFormData();

        if (!$model->save($data)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $model->errors()));
        }

        return redirect()->to(site_url('admin/tracking'))->with('message', 'Link criado com sucesso!');
    }

    // ── Edit / Update ──────────────────────────────────────────────────────────
    public function edit(int $id): string
    {
        $model = new TrackingLinkModel();
        $link  = $model->find($id);

        if (!$link) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('admin/tracking/form', ['link' => $link]);
    }

    public function update(int $id)
    {
        $model = new TrackingLinkModel();
        $data  = $this->getFormData();
        $data['id'] = $id;

        if (!$model->save($data)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $model->errors()));
        }

        return redirect()->to(site_url('admin/tracking'))->with('message', 'Link atualizado!');
    }

    // ── Destroy ────────────────────────────────────────────────────────────────
    public function destroy(int $id)
    {
        $linkModel = new TrackingLinkModel();
        $hitModel  = new TrackingHitModel();

        if (!$linkModel->find($id)) {
            return redirect()->to(site_url('admin/tracking'))->with('error', 'Link não encontrado.');
        }

        $hitModel->where('tracking_link_id', $id)->delete();
        $linkModel->delete($id);

        return redirect()->to(site_url('admin/tracking'))->with('message', 'Link e seus registros removidos.');
    }

    // ── Toggle Active ──────────────────────────────────────────────────────────
    public function toggleActive(int $id)
    {
        $model = new TrackingLinkModel();
        $link  = $model->find($id);

        if (!$link) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Not found']);
        }

        $model->update($id, ['is_active' => $link->is_active ? 0 : 1]);

        return redirect()->to(site_url('admin/tracking'))->with('message', 'Status alterado.');
    }

    // ── Dashboard ──────────────────────────────────────────────────────
    public function dashboard(): string
    {
        $hitModel  = new TrackingHitModel();
        $linkModel = new TrackingLinkModel();

        $from        = $this->request->getGet('date_from') ?? date('Y-m-01');
        $to          = $this->request->getGet('date_to')   ?? date('Y-m-d');
        $linkId      = (int) ($this->request->getGet('link_id') ?? 0) ?: null;
        $excludeBots = (bool) ($this->request->getGet('exclude_bots') ?? 1);

        // Ensure $to includes the full day
        $toFull = $to . ' 23:59:59';

        return view('admin/tracking/dashboard', [
            'from'         => $from,
            'to'           => $to,
            'selectedLink' => $linkId,
            'excludeBots'  => $excludeBots,
            'links'        => $linkModel->findAll(),
            'total'        => $hitModel->totalHits($from, $toFull, $linkId),
            'unique'       => $hitModel->uniqueVisitors($from, $toFull, $linkId, $excludeBots),
            'botCount'     => $hitModel->botHits($from, $toFull, $linkId),
            'bySource'     => $hitModel->hitsBySource($from, $toFull, $excludeBots),
            'byCampaign'   => $hitModel->hitsByCampaign($from, $toFull, $excludeBots),
            'byDay'        => $hitModel->hitsByDay($from, $toFull, $linkId, $excludeBots),
            'byCity'       => $hitModel->hitsByCity($from, $toFull, 10, $excludeBots),
            'byDevice'     => $hitModel->hitsByDevice($from, $toFull, $excludeBots),
            'byBrowser'    => $hitModel->hitsByBrowser($from, $toFull, $linkId),
            'recentHits'   => $hitModel->recentHits(15, $linkId),
        ]);
    }

    // ── Helper ─────────────────────────────────────────────────────────────────
    private function getFormData(): array
    {
        return [
            'slug'            => strtolower(trim($this->request->getPost('slug') ?? '')),
            'destination_url' => trim($this->request->getPost('destination_url') ?? ''),
            'utm_source'      => trim($this->request->getPost('utm_source') ?? '') ?: null,
            'utm_medium'      => trim($this->request->getPost('utm_medium') ?? '') ?: null,
            'utm_campaign'    => trim($this->request->getPost('utm_campaign') ?? '') ?: null,
            'utm_content'     => trim($this->request->getPost('utm_content') ?? '') ?: null,
            'is_active'       => (int) ($this->request->getPost('is_active') ?? 1),
        ];
    }
}
