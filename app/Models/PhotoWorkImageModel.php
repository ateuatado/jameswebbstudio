<?php

namespace App\Models;

use CodeIgniter\Model;

class PhotoWorkImageModel extends Model
{
    protected $table = 'photo_work_images';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['photo_work_id', 'image_path', 'alt_text', 'is_cover', 'is_for_sale', 'accepts_custom_sizes', 'custom_size_note', 'display_order'];
}
