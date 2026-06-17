<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientProjectModel extends Model
{
    protected $table            = 'client_projects';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'user_id', 'package_id', 'status', 's3_folder'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Callbacks
    protected $afterInsert = ['generateS3Folder'];

    // Validation
    protected $validationRules      = [
        'name'       => 'required|min_length[3]|max_length[255]',
        'user_id'    => 'required|is_natural_no_zero',
        'package_id' => 'required|is_natural_no_zero',
        'status'     => 'required|in_list[open,selecting,paid,completed]',
        's3_folder'  => 'permit_empty|max_length[255]',
    ];

    /**
     * Gera e salva a nomenclatura amigavel da pasta no S3 apos o insert
     */
    protected function generateS3Folder(array $data)
    {
        if (empty($data['id'])) {
            return $data;
        }

        $id = $data['id'];
        $db = \Config\Database::connect();
        
        $project = $db->table('client_projects')->where('id', $id)->get()->getRow();
        if (!$project) {
            return $data;
        }
        
        // Busca usuario para extrair o slug
        $user = $db->table('users')->where('id', $project->user_id)->get()->getRow();
        $username = $user->username ?? 'cliente';
        
        // Normaliza para letras minusculas e apenas caracteres alfanumericos
        $slug = strtolower(preg_replace('/[^A-Za-z0-9]+/', '', $username));
        if (empty($slug)) {
            $slug = 'cliente';
        }
        
        $s3Folder = $slug . '_' . $id;
        
        // Atualiza a coluna s3_folder de forma direta no banco
        $db->table('client_projects')->where('id', $id)->update(['s3_folder' => $s3Folder]);
        
        return $data;
    }
}
