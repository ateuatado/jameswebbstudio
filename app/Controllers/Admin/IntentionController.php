<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Intention;

class IntentionController extends BaseController
{
    public function index()
    {
        $model = new Intention();
        $data['intentions'] = $model->select('intentions.*, heroes.name as hero_name, bookings.id as booking_id')
                                    ->join('heroes', 'heroes.id = intentions.hero_id')
                                    ->join('bookings', 'bookings.intention_id = intentions.id', 'left')
                                    ->orderBy('intentions.created_at', 'DESC')
                                    ->findAll();

        return view('admin/intentions/index', $data);
    }

    public function delete($id)
    {
        $model = new Intention();
        if ($model->delete($id)) {
            return redirect()->back()->with('message', 'Lead removido com sucesso.');
        }
        return redirect()->back()->with('error', 'Erro ao remover lead.');
    }
}
