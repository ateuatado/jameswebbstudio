<?php

namespace App\Controllers;

use App\Models\Hero;
use App\Models\Cta;
use App\Models\CtaBlock;

class LandingPage extends BaseController
{
    public function view($slug)
    {
        $heroModel = new Hero();
        $hero = $heroModel->where('slug', $slug)->where('published', 1)->first();

        if (!$hero) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $ctaModel   = new Cta();
        $blockModel = new CtaBlock();

        $cta    = $ctaModel->where('hero_id', $hero['id'])->first();
        $blocks = $cta ? $blockModel->blocksForCta((int)$cta['id']) : [];

        return view('landing_page', [
            'hero'   => $hero,
            'cta'    => $cta,
            'blocks' => $blocks,
            'title'  => ($cta['title'] ?? $hero['name']) . ' | James Webb Studio',
        ]);
    }
}
