<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\ServiceModel;

class PackageController extends BaseController
{
    protected $packageModel;

    public function __construct()
    {
        $this->packageModel = new PackageModel();
    }

    public function index()
    {
        $categoryModel = new \App\Models\CategoryModel();
        $categories = $categoryModel->findAll();
        
        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat->id] = $cat->name;
        }

        $packages = $this->packageModel->findAll();
        $db = \Config\Database::connect();

        foreach ($packages as &$pkg) {
            $pkg->category_name = $categoryMap[$pkg->category_id ?? 0] ?? 'Sem Categoria';
            // Conta serviços vinculados
            $pkg->service_count = $db->table('package_services')
                                     ->where('package_id', $pkg->id)
                                     ->countAllResults();
            // Soma custo dos serviços
            $pkg->services_total = $db->table('package_services')
                                      ->join('services', 'services.id = package_services.service_id')
                                      ->where('package_id', $pkg->id)
                                      ->selectSum('services.price')
                                      ->get()->getRow()->price ?? 0;
        }
        unset($pkg);

        $data = [
            'title'    => 'Pacotes',
            'packages' => $packages
        ];
        return view('admin/packages/index', $data);
    }

    public function new()
    {
        $categoryModel = new \App\Models\CategoryModel();
        $serviceModel  = new ServiceModel();

        return view('admin/packages/form', [
            'title'           => 'Novo Pacote',
            'categories'      => $categoryModel->where('is_active', 1)->orderBy('name', 'asc')->findAll(),
            'servicesGrouped' => $this->_groupedServices($serviceModel),
            'selectedServices'=> [],
        ]);
    }

    public function create()
    {
        $data = $this->request->getPost();
        $serviceIds = $data['services'] ?? [];
        unset($data['services']);

        $data['is_active']    = isset($data['is_active']) ? 1 : 0;
        $data['is_preferred'] = isset($data['is_preferred']) ? 1 : 0;
        
        if (empty($data['category_id'])) {
            $data['category_id'] = null;
        }
        
        if ($this->packageModel->save($data)) {
            $packageId = $this->packageModel->getInsertID();
            $this->_syncServices($packageId, $serviceIds);
            return redirect()->to('/admin/packages')->with('message', 'Pacote criado com sucesso.');
        }

        return redirect()->back()->withInput()->with('errors', $this->packageModel->errors());
    }

    public function edit($id = null)
    {
        $package = $this->packageModel->find($id);
        if (!$package) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $categoryModel = new \App\Models\CategoryModel();
        $serviceModel  = new ServiceModel();

        $db = \Config\Database::connect();
        $selected = $db->table('package_services')
                       ->where('package_id', $id)
                       ->get()->getResultArray();
        $selectedIds = array_column($selected, 'service_id');

        return view('admin/packages/form', [
            'title'            => 'Editar Pacote',
            'package'          => $package,
            'categories'       => $categoryModel->where('is_active', 1)->orderBy('name', 'asc')->findAll(),
            'servicesGrouped'  => $this->_groupedServices($serviceModel),
            'selectedServices' => $selectedIds,
        ]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        $serviceIds = $data['services'] ?? [];
        unset($data['services']);

        $data['id'] = $id;
        $data['is_active']    = isset($data['is_active']) ? 1 : 0;
        $data['is_preferred'] = isset($data['is_preferred']) ? 1 : 0;

        if (empty($data['category_id'])) {
            $data['category_id'] = null;
        }

        if ($this->packageModel->save($data)) {
            $this->_syncServices($id, $serviceIds);
            return redirect()->to('/admin/packages')->with('message', 'Pacote atualizado com sucesso.');
        }

        return redirect()->back()->withInput()->with('errors', $this->packageModel->errors());
    }

    public function delete($id = null)
    {
        $this->packageModel->delete($id);
        return redirect()->to('/admin/packages')->with('message', 'Pacote removido com sucesso.');
    }

    // ----------------------------------------------------------------
    // Helpers
    // ----------------------------------------------------------------

    /**
     * Agrupa serviços ativos por fase.
     */
    private function _groupedServices(ServiceModel $model): array
    {
        $services = $model->where('is_active', 1)->orderBy('phase', 'asc')->orderBy('name', 'asc')->findAll();
        $grouped = [];
        foreach (ServiceModel::PHASE_LABELS as $phase => $label) {
            $grouped[$phase] = ['label' => $label, 'services' => []];
        }
        foreach ($services as $s) {
            $grouped[$s->phase]['services'][] = $s;
        }
        return $grouped;
    }

    /**
     * Sincroniza serviços de um pacote (delete + insert).
     */
    private function _syncServices(int $packageId, array $serviceIds): void
    {
        $db = \Config\Database::connect();
        $db->table('package_services')->where('package_id', $packageId)->delete();

        $batch = [];
        foreach ($serviceIds as $sid) {
            $batch[] = ['package_id' => $packageId, 'service_id' => (int)$sid];
        }
        if (!empty($batch)) {
            $db->table('package_services')->insertBatch($batch);
        }
    }
}
