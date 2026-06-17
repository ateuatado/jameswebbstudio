<?php

namespace App\Models;

use CodeIgniter\Model;

class Hero extends Model
{
    protected $table            = 'heroes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'sport', 'category_id', 'slug', 'cover_photo_id', 'published'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'id'          => 'permit_empty|is_natural_no_zero',
        'name'        => 'required|max_length[255]',
        'sport'       => 'permit_empty|max_length[255]',
        'category_id' => 'permit_empty|is_natural_no_zero',
        'slug'        => 'required|alpha_dash|max_length[255]|is_unique[heroes.slug,id,{id}]',
        'published'   => 'permit_empty|in_list[0,1]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
