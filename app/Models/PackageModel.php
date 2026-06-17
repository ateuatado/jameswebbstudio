<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageModel extends Model
{
    protected $table            = 'packages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'base_price', 'category_id', 'description', 'internal_notes', 'included_photos', 'extra_photo_price', 'is_active', 'is_preferred'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'name'              => 'required|max_length[100]',
        'base_price'        => 'required|decimal',
        'category_id'       => 'permit_empty|is_natural_no_zero',
        'description'       => 'permit_empty',
        'internal_notes'    => 'permit_empty',
        'included_photos'   => 'required|is_natural',
        'extra_photo_price' => 'required|decimal',
        'is_active'         => 'permit_empty|in_list[0,1]',
        'is_preferred'      => 'permit_empty|in_list[0,1]',
    ];
}
