<?php

namespace App\Controllers;

use App\Models\Availability;
use App\Models\Booking;
use CodeIgniter\API\ResponseTrait;

class ScheduleController extends BaseController
{
    use ResponseTrait;

    public function getSlots($heroId)
    {
        $model = new Availability();
        $slots = $model->where('hero_id', $heroId)
                       ->where('is_booked', 0)
                       ->where('date >=', date('Y-m-d'))
                       ->orderBy('date', 'ASC')
                       ->orderBy('start_time', 'ASC')
                       ->findAll();

        return $this->respond($slots);
    }

    public function book()
    {
        $rules = [
            'availability_id' => 'required|is_natural_no_zero',
            'name'            => 'required|min_length[3]',
            'email'           => 'required|valid_email',
            'phone'           => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $availModel = new Availability();
        $bookingModel = new Booking();

        $availId = $this->request->getPost('availability_id');
        $avail = $availModel->find($availId);

        if (!$avail || $avail['is_booked']) {
            return $this->fail('Este horário não está mais disponível.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $bookingModel->insert([
            'availability_id' => $availId,
            'name'            => $this->request->getPost('name'),
            'email'           => $this->request->getPost('email'),
            'phone'           => $this->request->getPost('phone'),
            'notes'           => $this->request->getPost('notes'),
            'status'          => 'confirmed',
        ]);

        $availModel->update($availId, ['is_booked' => 1]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->fail('Falha ao processar agendamento.');
        }

        return $this->respondCreated(['message' => 'Agendamento realizado com sucesso!']);
    }
}
