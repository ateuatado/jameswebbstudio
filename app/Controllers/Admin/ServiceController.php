<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ServiceModel;

class ServiceController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new ServiceModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $services = $this->model->orderBy('phase', 'asc')->orderBy('name', 'asc')->findAll();

        // Agrupa por fase
        $grouped = [];
        foreach (ServiceModel::PHASE_LABELS as $phase => $label) {
            $grouped[$phase] = [
                'label'    => $label,
                'services' => [],
            ];
        }
        foreach ($services as $s) {
            $grouped[$s->phase]['services'][] = $s;
        }

        return view('admin/services/index', ['grouped' => $grouped]);
    }

    public function new()
    {
        return view('admin/services/form', [
            'title'  => 'Novo Serviço',
            'phases' => ServiceModel::PHASE_LABELS,
        ]);
    }

    public function create()
    {
        if (!$this->validate($this->model->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->save([
            'phase'       => $this->request->getPost('phase'),
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'is_active'   => $this->request->getPost('is_active') ?? 1,
        ]);

        return redirect()->to(site_url('admin/services'))->with('message', 'Serviço criado com sucesso.');
    }

    public function edit($id = null)
    {
        $service = $this->model->find($id);
        if (!$service) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        return view('admin/services/form', [
            'title'   => 'Editar Serviço',
            'service' => $service,
            'phases'  => ServiceModel::PHASE_LABELS,
        ]);
    }

    public function update($id = null)
    {
        if (!$this->validate($this->model->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->update($id, [
            'phase'       => $this->request->getPost('phase'),
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'is_active'   => $this->request->getPost('is_active') ?? 1,
        ]);

        return redirect()->to(site_url('admin/services'))->with('message', 'Serviço atualizado.');
    }

    public function delete($id = null)
    {
        $this->model->delete($id);
        return redirect()->to(site_url('admin/services'))->with('message', 'Serviço excluído.');
    }
}
