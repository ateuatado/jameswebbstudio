<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\CategoryModel;
use App\Models\ServiceModel;

class Pricing extends BaseController
{
    public function index()
    {
        $packageModel = new PackageModel();
        $catModel     = new CategoryModel();
        $db           = \Config\Database::connect();

        $allPackages = $packageModel->where('is_active', 1)->orderBy('base_price', 'asc')->findAll();
        $categories  = $catModel->where('is_active', 1)->findAll();

        $catMap     = [];
        $catDescMap = [];
        foreach ($categories as $cat) {
            $catMap[$cat->id]          = $cat->name;
            $catDescMap[$cat->name]    = $cat->description ?? '';
        }

        // Carrega serviços de cada pacote e agrupa por categoria
        $grouped = [];
        foreach ($allPackages as $pkg) {
            $pkg->services = $db->table('package_services')
                                ->join('services', 'services.id = package_services.service_id')
                                ->where('package_services.package_id', $pkg->id)
                                ->where('services.is_active', 1)
                                ->orderBy('services.phase', 'asc')
                                ->orderBy('services.name', 'asc')
                                ->get()->getResultObject();

            $catName = $catMap[$pkg->category_id ?? 0] ?? 'Ensaio Fotográfico';
            $grouped[$catName][] = $pkg;
        }

        return view('pricing', [
            'title'      => 'Investimento | James Webb Studio',
            'grouped'    => $grouped,
            'catDescMap' => $catDescMap,
        ]);
    }
}
