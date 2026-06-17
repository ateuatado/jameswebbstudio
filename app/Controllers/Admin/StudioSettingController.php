<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StudioSettingModel;

class StudioSettingController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new StudioSettingModel();
    }

    public function index()
    {
        $settings = $this->model->orderBy('id', 'asc')->findAll();

        return view('admin/studio/index', [
            'settings' => $settings,
        ]);
    }

    public function update()
    {
        $settings = $this->model->findAll();

        foreach ($settings as $s) {
            $newValue = $this->request->getPost($s->setting_key);
            if ($newValue !== null) {
                $this->model->setValue($s->setting_key, trim($newValue));
            }
        }

        return redirect()->to('/admin/studio')->with('message', 'Dados do estúdio salvos com sucesso.');
    }
}
