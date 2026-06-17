<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class BookingController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('bookings');
        $builder->select('bookings.*, availabilities.date, availabilities.start_time, availabilities.end_time, availabilities.type, heroes.name as hero_name');
        $builder->join('availabilities', 'availabilities.id = bookings.availability_id');
        $builder->join('heroes', 'heroes.id = availabilities.hero_id');
        $builder->orderBy('availabilities.date', 'DESC');
        $builder->orderBy('availabilities.start_time', 'DESC');
        
        $data['bookings'] = $builder->get()->getResultArray();

        return view('admin/bookings/index', $data);
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $booking = $db->table('bookings')->where('id', $id)->get()->getRowArray();
        
        if ($booking) {
            $db->transStart();
            // Free the slot
            $db->table('availabilities')->where('id', $booking['availability_id'])->update(['is_booked' => 0]);
            // Delete the booking
            $db->table('bookings')->where('id', $id)->delete();
            $db->transComplete();
            
            return redirect()->to('admin/bookings')->with('message', 'Agendamento removido e slot liberado.');
        }

        return redirect()->to('admin/bookings')->with('error', 'Agendamento não encontrado.');
    }
}
