<?php

namespace App\Controllers;

use App\Models\Intention;
use App\Models\Booking;
use App\Models\Availability;
use CodeIgniter\API\ResponseTrait;

class IntentionController extends BaseController
{
    use ResponseTrait;

    public function store()
    {
        $rules = [
            'hero_id'    => 'required|is_natural_no_zero',
            'name'       => 'required|min_length[3]',
            'email'      => 'required|valid_email',
            'phone'      => 'required|min_length[8]',
            'address'    => 'required',
            'age'        => 'required|is_natural',
            'shoot_type' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $intentionModel = new Intention();
        $data = [
            'hero_id'    => $this->request->getPost('hero_id'),
            'name'       => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'phone'      => $this->request->getPost('phone'),
            'address'    => $this->request->getPost('address'),
            'age'        => $this->request->getPost('age'),
            'shoot_type' => $this->request->getPost('shoot_type'),
            'status'     => 'new',
        ];

        $intentionId = $intentionModel->insert($data);

        // Optional Booking
        $availId = $this->request->getPost('availability_id');
        if ($availId) {
            $availModel = new Availability();
            $bookingModel = new Booking();

            $avail = $availModel->find($availId);
            if ($avail && !$avail['is_booked']) {
                $bookingModel->insert([
                    'availability_id' => $availId,
                    'intention_id'    => $intentionId,
                    'name'            => $data['name'],
                    'email'           => $data['email'],
                    'phone'           => $data['phone'],
                    'notes'           => 'Agendamento automático via formulário de intenção.',
                    'status'          => 'confirmed',
                ]);

                $availModel->update($availId, ['is_booked' => 1]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->fail('Falha ao processar solicitação.');
        }

        return $this->respondCreated(['message' => 'Solicitação recebida com sucesso!']);
    }
}
