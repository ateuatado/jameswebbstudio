<?php

namespace App\Models;

use CodeIgniter\Model;

class ContractSectionModel extends Model
{
    protected $table            = 'contract_sections';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'content', 'display_order', 'is_active'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'id'            => 'permit_empty|is_natural_no_zero',
        'title'         => 'required|max_length[200]',
        'content'       => 'required',
        'display_order' => 'permit_empty|is_natural',
        'is_active'     => 'permit_empty|in_list[0,1]',
    ];

    /**
     * Retorna todas as cláusulas ativas, ordenadas por display_order.
     */
    public function getActive(): array
    {
        return $this->where('is_active', 1)
                    ->orderBy('display_order', 'asc')
                    ->findAll();
    }
}
