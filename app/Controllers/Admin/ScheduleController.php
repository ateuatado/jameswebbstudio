<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Availability;
use App\Models\Hero;

class ScheduleController extends BaseController
{
    public function index($heroId)
    {
        $heroModel = new Hero();
        $availModel = new Availability();
        
        $data['hero'] = $heroModel->find($heroId);
        if (!$data['hero']) {
            return redirect()->to('admin/heroes')->with('error', 'Herói não encontrado.');
        }

        $data['availabilities'] = $availModel->where('hero_id', $heroId)
                                            ->orderBy('date', 'ASC')
                                            ->orderBy('start_time', 'ASC')
                                            ->findAll();
        
        return view('admin/schedules/index', $data);
    }

    public function store($heroId)
    {
        $model = new Availability();
        $data = [
            'hero_id'    => $heroId,
            'date'       => $this->request->getPost('date'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time'   => $this->request->getPost('end_time'),
            'type'       => $this->request->getPost('type'),
            'is_booked'  => 0,
        ];

        if ($model->insert($data)) {
            return redirect()->back()->with('message', 'Horário adicionado com sucesso!');
        }
        return redirect()->back()->with('error', 'Erro ao adicionar horário.');
    }

    public function bulk($heroId)
    {
        $model = new Availability();
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');
        $days = $this->request->getPost('days'); // Array of 1 (Mon) to 7 (Sun)
        $startTime = $this->request->getPost('start_time');
        $endTime = $this->request->getPost('end_time');
        $type = $this->request->getPost('type');

        if (!$startDate || !$endDate || empty($days)) {
            return redirect()->back()->with('error', 'Preencha todos os campos do lote.');
        }

        $current = strtotime($startDate);
        $last = strtotime($endDate);
        $count = 0;

        while ($current <= $last) {
            $dayOfWeek = date('N', $current); // 1 (Mon) to 7 (Sun)
            if (in_array($dayOfWeek, $days)) {
                $model->insert([
                    'hero_id'    => $heroId,
                    'date'       => date('Y-m-d', $current),
                    'start_time' => $startTime,
                    'end_time'   => $endTime,
                    'type'       => $type,
                    'is_booked'  => 0,
                ]);
                $count++;
            }
            $current = strtotime('+1 day', $current);
        }

        return redirect()->back()->with('message', "$count horários gerados em lote com sucesso!");
    }

    public function delete($id)
    {
        $model = new Availability();
        if ($model->delete($id)) {
            return redirect()->back()->with('message', 'Horário removido.');
        }
        return redirect()->back()->with('error', 'Erro ao remover.');
    }
}
