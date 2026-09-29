<?php

namespace App\Models;

use CodeIgniter\Model;

class PhotoWorkModel extends Model
{
    protected $table = 'photo_works';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['title', 'slug', 'short_description', 'story', 'work_date', 'location', 'technique', 'collection_name', 'credits', 'is_published', 'published_at', 'display_order'];
    protected $validationRules = [
        'id'    => 'permit_empty|is_natural_no_zero',
        'title' => 'required|max_length[255]',
        'slug'  => 'required|alpha_dash|max_length[255]|is_unique[photo_works.slug,id,{id}]',
    ];
}
