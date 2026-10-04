<?php

namespace App\Controllers;

use App\Models\ThreadsOpportunityModel;

class ThreadsInvitationController extends BaseController
{
    public function show(string $token)
    {
        $model = new ThreadsOpportunityModel();
        $opportunity = $model->findByToken($token);
        if (!$opportunity) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        \Config\Database::connect()->table('threads_opportunity_events')->insert([
            'opportunity_id' => $opportunity->id, 'event_type' => 'page_view', 'created_at' => date('Y-m-d H:i:s'),
        ]);

        return view('threads/invitation', ['opportunity' => $opportunity]);
    }
}
