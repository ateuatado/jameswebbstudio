<?php

namespace App\Models;

use CodeIgniter\Model;

class GuideSectionModel extends Model
{
    protected $table            = 'guide_sections';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['category_id', 'title', 'content', 'display_order', 'is_active'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'id'            => 'permit_empty|is_natural_no_zero',
        'title'         => 'required|max_length[200]',
        'content'       => 'required',
        'category_id'   => 'permit_empty|is_natural_no_zero',
        'display_order' => 'permit_empty|is_natural',
        'is_active'     => 'permit_empty|in_list[0,1]',
    ];

    /**
     * Retorna seções universais (sem categoria vinculada).
     */
    public function getUniversal(): array
    {
        return $this->where('category_id', null)
                    ->where('is_active', 1)
                    ->orderBy('display_order', 'asc')
                    ->findAll();
    }

    /**
     * Retorna seções universais + específicas de uma categoria, ordenadas.
     */
    public function getForCategory(?int $catId = null): array
    {
        $builder = $this->where('is_active', 1);

        if ($catId) {
            $builder->groupStart()
                        ->where('category_id', null)
                        ->orWhere('category_id', $catId)
                    ->groupEnd();
        } else {
            $builder->where('category_id', null);
        }

        return $builder->orderBy('category_id IS NOT NULL', 'asc', false) // universais primeiro
                       ->orderBy('display_order', 'asc')
                       ->findAll();
    }
}
