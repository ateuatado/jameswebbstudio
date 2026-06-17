<?php

namespace App\Models;

use CodeIgniter\Model;

class StudioSettingModel extends Model
{
    protected $table            = 'studio_settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['setting_key', 'setting_value', 'label'];
    protected $useTimestamps    = false;

    protected $validationRules = [
        'id'           => 'permit_empty|is_natural_no_zero',
        'setting_key'  => 'required|max_length[100]',
        'label'        => 'required|max_length[150]',
    ];

    /**
     * Retorna todas as configurações como array associativo [key => value].
     */
    public function getAll(): array
    {
        $rows = $this->findAll();
        $map  = [];
        foreach ($rows as $r) {
            $map[$r->setting_key] = $r->setting_value;
        }
        return $map;
    }

    /**
     * Retorna o valor de uma configuração específica.
     */
    public function getValue(string $key, string $default = ''): string
    {
        $row = $this->where('setting_key', $key)->first();
        return $row ? ($row->setting_value ?: $default) : $default;
    }

    /**
     * Atualiza o valor de uma configuração.
     */
    public function setValue(string $key, string $value): bool
    {
        return (bool) $this->where('setting_key', $key)->set(['setting_value' => $value])->update();
    }
}
