<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table            = 'services';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['phase', 'name', 'description', 'price', 'is_active'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'phase'       => 'required|in_list[pre_ensaio,durante,pos_producao,entregaveis,exclusividade]',
        'name'        => 'required|min_length[3]|max_length[255]',
        'description' => 'permit_empty',
        'price'       => 'required|decimal',
        'is_active'   => 'permit_empty|in_list[0,1]',
    ];

    /**
     * Labels legíveis para cada fase.
     */
    public const PHASE_LABELS = [
        'pre_ensaio'      => 'Pré-Ensaio',
        'durante'         => 'Durante o Ensaio',
        'pos_producao'    => 'Pós-Produção',
        'entregaveis'     => 'Entregáveis',
        'exclusividade'   => 'Exclusividade & Bônus',
    ];

    public static function phaseLabel(string $phase): string
    {
        return self::PHASE_LABELS[$phase] ?? $phase;
    }
}
