<?php

namespace App\Controllers;

class LegalController extends BaseController
{
    public function privacy()
    {
        return view('legal/page', ['title' => 'Política de Privacidade | James Webb Studio', 'heading' => 'Política de Privacidade', 'page' => 'privacy']);
    }

    public function terms()
    {
        return view('legal/page', ['title' => 'Termos de Serviço | James Webb Studio', 'heading' => 'Termos de Serviço', 'page' => 'terms']);
    }
}
