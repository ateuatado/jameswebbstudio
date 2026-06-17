<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'slug', 'description', 'is_active'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'id'          => 'permit_empty|is_natural_no_zero',
        'name'        => 'required|min_length[3]|max_length[100]',
        'slug'        => 'permit_empty|alpha_dash|max_length[100]|is_unique[categories.slug,id,{id}]',
        'description' => 'permit_empty',
        'is_active'   => 'permit_empty|in_list[0,1]',
    ];

    // Hooks
    protected $beforeInsert = ['generateSlug'];
    protected $beforeUpdate = ['generateSlug'];

    /**
     * Autogera um slug amigável se não for fornecido manualmente.
     */
    protected function generateSlug(array $data)
    {
        if (isset($data['data']['name']) && empty($data['data']['slug'])) {
            // mb_url_title é o helper nativo do CI4 para slugs com suporte UTF8
            helper('text');
            $data['data']['slug'] = mb_url_title($data['data']['name'], '-', true);
        }
        return $data;
    }
}
