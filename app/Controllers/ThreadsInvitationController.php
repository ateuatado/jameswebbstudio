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

    public function decline(string $token)
    {
        $model = new ThreadsOpportunityModel();
        $opportunity = $model->where('page_token', $token)->first();
        if (!$opportunity) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        if ($opportunity->status !== 'do_not_contact' || (int) $opportunity->is_page_active === 1) {
            $now = date('Y-m-d H:i:s');
            $model->update($opportunity->id, [
                'status' => 'do_not_contact',
                'is_page_active' => 0,
                'do_not_contact_at' => $now,
                'closed_reason' => 'Pedido da pessoa abordada: não deseja receber o convite.',
                'last_action_at' => $now,
            ]);

            \Config\Database::connect()->table('threads_opportunity_events')->insert([
                'opportunity_id' => $opportunity->id,
                'event_type' => 'invite_declined',
                'actor_user_id' => null,
                'details' => json_encode(['source' => 'public_page'], JSON_UNESCAPED_UNICODE),
                'created_at' => $now,
            ]);
        }

        return view('threads/declined');
    }
}
