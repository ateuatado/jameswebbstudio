<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table         = 'orders';
    protected $primaryKey    = 'id';
    protected $returnType    = 'object';
    protected $useSoftDeletes = false;
    protected $useTimestamps = true;

    protected $allowedFields = [
        'mp_preference_id',
        'mp_payment_id',
        'package_id',
        'hero_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'amount',
        'status',
        'agenda_link',
        'mp_raw',
        // Dados contratuais
        'cpf',
        'rg',
        'marital_status',
        'address',
        'city',
        'state',
        'zip_code',
        'image_usage_authorized',
        'accepted_terms_at',
    ];

    protected $validationRules = [
        'buyer_name'  => 'required',
        'buyer_email' => 'required|valid_email',
        'amount'      => 'required|numeric',
    ];

    // ── Scopes úteis ─────────────────────────────────────────────────────────

    public function approved()
    {
        return $this->where('status', 'approved');
    }

    public function pending()
    {
        return $this->where('status', 'pending');
    }

    /**
     * Busca order pelo mp_preference_id (retornado ao criar a Preference)
     */
    public function findByPreferenceId(string $prefId)
    {
        return $this->where('mp_preference_id', $prefId)->first();
    }

    /**
     * Resumo para o dashboard: contagem por status
     */
    public function summary(): array
    {
        $rows = $this->select('status, COUNT(*) as total, SUM(amount) as revenue')
                     ->groupBy('status')
                     ->findAll();
        $result = ['approved' => 0, 'pending' => 0, 'cancelled' => 0, 'revenue' => 0];
        foreach ($rows as $r) {
            $result[$r->status] = (int) $r->total;
            if ($r->status === 'approved') {
                $result['revenue'] = (float) $r->revenue;
            }
        }
        return $result;
    }
}
