<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuideSectionModel;
use App\Models\CategoryModel;

class GuideSectionController extends BaseController
{
    protected $model;
    protected $catModel;

    public function __construct()
    {
        $this->model    = new GuideSectionModel();
        $this->catModel = new CategoryModel();
    }

    // ── Lista todas as seções ──
    public function index()
    {
        $sections   = $this->model->orderBy('category_id IS NULL', 'desc', false)
                                  ->orderBy('category_id', 'asc')
                                  ->orderBy('display_order', 'asc')
                                  ->findAll();
        $categories = $this->catModel->where('is_active', 1)->findAll();
        $catMap     = ['' => 'Universal (todos os ensaios)'];
        foreach ($categories as $c) {
            $catMap[$c->id] = $c->name;
        }

        // Agrupa
        $grouped = [];
        foreach ($sections as $s) {
            $key = $s->category_id ?: '';
            $grouped[$key][] = $s;
        }

        return view('admin/guide/index', [
            'grouped'    => $grouped,
            'catMap'     => $catMap,
            'categories' => $categories,
        ]);
    }

    // ── Form nova seção ──
    public function create()
    {
        $categories = $this->catModel->where('is_active', 1)->findAll();
        return view('admin/guide/form', [
            'section'    => null,
            'categories' => $categories,
        ]);
    }

    // ── Salvar nova ──
    public function store()
    {
        $catId = $this->request->getPost('category_id');

        $data = [
            'title'         => trim($this->request->getPost('title')),
            'content'       => trim($this->request->getPost('content')),
            'category_id'   => $catId ?: null,
            'display_order' => (int) $this->request->getPost('display_order'),
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if (!$this->model->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        return redirect()->to('/admin/guide-sections')->with('message', 'Seção criada com sucesso.');
    }

    // ── Form editar ──
    public function edit($id)
    {
        $section = $this->model->find($id);
        if (!$section) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $categories = $this->catModel->where('is_active', 1)->findAll();
        return view('admin/guide/form', [
            'section'    => $section,
            'categories' => $categories,
        ]);
    }

    // ── Atualizar ──
    public function update($id)
    {
        $section = $this->model->find($id);
        if (!$section) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $catId = $this->request->getPost('category_id');

        $data = [
            'title'         => trim($this->request->getPost('title')),
            'content'       => trim($this->request->getPost('content')),
            'category_id'   => $catId ?: null,
            'display_order' => (int) $this->request->getPost('display_order'),
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if (!$this->model->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        return redirect()->to('/admin/guide-sections')->with('message', 'Seção atualizada com sucesso.');
    }

    // ── Excluir ──
    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->to('/admin/guide-sections')->with('message', 'Seção removida.');
    }

    // ── Preview PDF ──
    public function preview()
    {
        $catId = $this->request->getGet('category_id') ?: null;
        $generator = new \App\Libraries\GuideGenerator();
        $pdf = $generator->generate('Cliente Exemplo', 'exemplo@email.com', $catId, date('d/m/Y'));

        return $this->response
                     ->setHeader('Content-Type', 'application/pdf')
                     ->setHeader('Content-Disposition', 'inline; filename="guia-pre-ensaio-preview.pdf"')
                     ->setBody($pdf);
    }
}
