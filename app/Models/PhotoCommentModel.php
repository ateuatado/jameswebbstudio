<?php

namespace App\Models;

use CodeIgniter\Model;

class PhotoCommentModel extends Model
{
    protected $table = 'photo_comments';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['photo_work_image_id', 'user_id', 'body', 'is_published'];
}
