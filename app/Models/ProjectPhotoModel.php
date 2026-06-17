<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectPhotoModel extends Model
{
    protected $table            = 'project_photos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['project_id', 'original_filename', 'proxy_url', 'final_url', 'status', 'is_loved', 'rating', 'ai_description', 'ai_tags'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'project_id'        => 'required|is_natural_no_zero',
        'original_filename' => 'required|max_length[255]',
        'proxy_url'         => 'required|max_length[500]',
        'final_url'         => 'permit_empty|max_length[500]',
        'status'            => 'required|in_list[pending,selected,delivered]',
        'is_loved'          => 'permit_empty|in_list[0,1]',
        'rating'            => 'permit_empty|greater_than_equal_to[0]|less_than_equal_to[5]',
        'ai_description'    => 'permit_empty|string',
        'ai_tags'           => 'permit_empty|string',
    ];
}
