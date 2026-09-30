<?php

namespace App\Models;

use CodeIgniter\Model;

class PhotoImageViewModel extends Model
{
    protected $table = 'photo_image_views';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['photo_work_image_id', 'user_id', 'visitor_token', 'viewed_at'];
}
