<?php

namespace App\Models;

use CodeIgniter\Model;

class PhotoPrintOptionModel extends Model
{
    protected $table = 'photo_print_options';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['photo_work_image_id', 'size_label', 'width_cm', 'height_cm', 'price_cents', 'print_material', 'frame_material', 'backing_material', 'glazing', 'weight_grams', 'production_lead_time', 'is_available', 'display_order'];
    protected $validationRules = [
        'size_label' => 'required|max_length[100]',
        'price_cents' => 'required|is_natural',
    ];
}
