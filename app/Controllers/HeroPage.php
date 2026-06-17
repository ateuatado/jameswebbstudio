<?php

namespace App\Controllers;

use App\Models\Hero;
use App\Models\Photo;
use App\Models\Cta;
use App\Models\CtaBlock;
use App\Models\PackageModel;
use App\Models\CategoryModel;

class HeroPage extends BaseController
{
    public function view($slug)
    {
        $heroModel = new Hero();
        $hero = $heroModel->where('slug', $slug)->first();

        if (!$hero) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $photoModel   = new Photo();
        $ctaModel     = new Cta();
        $blockModel   = new CtaBlock();
        $packageModel = new PackageModel();
        $catModel     = new CategoryModel();

        // ── Fotos ────────────────────────────────────────────────────────────
        $allPhotos = $photoModel->where('hero_id', $hero['id'])->orderBy('display_order', 'asc')->findAll();
        if (!empty($hero['cover_photo_id'])) {
            $coverFirst = [];
            $rest       = [];
            foreach ($allPhotos as $p) {
                if ($p['id'] == $hero['cover_photo_id']) {
                    $coverFirst[] = $p;
                } else {
                    $rest[] = $p;
                }
            }
            $allPhotos = array_merge($coverFirst, $rest);
        }

        // ── CTA Blocks ───────────────────────────────────────────────────────
        $cta    = $ctaModel->where('hero_id', $hero['id'])->first();
        $blocks = $cta ? $blockModel->blocksForCta((int)$cta['id']) : [];

        // ── Pacotes ──────────────────────────────────────────────────────────
        $allPackages = $packageModel->where('is_active', 1)->orderBy('base_price', 'asc')->findAll();
        $heroCatId   = $hero['category_id'] ?? null;

        // Mapa de categorias
        $categories = $catModel->findAll();
        $catMap     = [];
        foreach ($categories as $cat) {
            $catMap[$cat->id] = $cat->name;
        }

        // Pacotes da mesma categoria do herói (destaque)
        $heroPackages  = [];
        // Pacotes de outras categorias, agrupados por categoria
        $otherPackages = [];

        // Carrega serviços de cada pacote via pivô
        $db = \Config\Database::connect();
        foreach ($allPackages as $pkg) {
            $pkg->services = $db->table('package_services')
                                ->join('services', 'services.id = package_services.service_id')
                                ->where('package_services.package_id', $pkg->id)
                                ->where('services.is_active', 1)
                                ->orderBy('services.phase', 'asc')
                                ->orderBy('services.name', 'asc')
                                ->get()->getResultObject();

            if ($heroCatId && $pkg->category_id == $heroCatId) {
                $heroPackages[] = $pkg;
            } else {
                $catName = $catMap[$pkg->category_id ?? 0] ?? 'Outros Ensaios';
                $otherPackages[$catName][] = $pkg;
            }
        }

        $data['hero']          = $hero;
        $data['photos']        = $allPhotos;
        $data['cta']           = $cta;
        $data['blocks']        = $blocks;
        $data['heroPackages']  = $heroPackages;
        $data['otherPackages'] = $otherPackages;
        $data['heroCatName']   = $catMap[$heroCatId ?? 0] ?? 'Ensaio Fotográfico';
        $data['title']         = $hero['name'] . ' | ' . $hero['sport'] . ' | James Webb Studio';

        return view('star_page', $data);
    }
}
