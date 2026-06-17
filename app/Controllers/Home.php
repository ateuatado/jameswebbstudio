<?php

namespace App\Controllers;

use App\Models\Hero;

class Home extends BaseController
{
    public function index()
    {
        // Redireciona cliente logado para a galeria
        if (auth()->loggedIn()) {
            $user = auth()->user();
            if ($user->inGroup('client')) {
                return redirect()->to('/client/galeria');
            }
            // Admin e superadmin veem a página pública normalmente
        }

        $heroModel = new Hero();
        $catModel  = new \App\Models\CategoryModel();

        $categories = $catModel->where('is_active', 1)->findAll();
        $catMap = [];
        foreach ($categories as $cat) {
            $catMap[$cat->id] = $cat->name;
        }

        $data['heroes'] = $heroModel->select('heroes.*, photos.image_path as cover_image')
                                    ->join('photos', 'photos.id = heroes.cover_photo_id', 'left')
                                    ->where('heroes.published', 1)
                                    ->orderBy('heroes.created_at', 'DESC')
                                    ->findAll();

        foreach ($data['heroes'] as &$h) {
            $h['category_name'] = $catMap[$h['category_id'] ?? 0] ?? '';
        }
        unset($h);
        
        return view('home', $data);
    }
}
