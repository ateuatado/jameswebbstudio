<?php

namespace App\Models;

use CodeIgniter\Model;

class HeroPublicationLog extends Model
{
    protected $table         = 'hero_publication_logs';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['hero_id', 'action', 'reason', 'performed_by', 'performed_by_name', 'created_at'];

    protected $useTimestamps  = false; // já temos created_at manual
    protected $protectFields  = true;

    /**
     * Registra uma entrada no log de publicação.
     */
    public function log(int $heroId, string $action, ?string $reason = null): void
    {
        $user     = auth()->user();
        $userId   = $user?->id;
        $userName = $user?->username ?? $user?->email ?? 'sistema';

        $this->insert([
            'hero_id'            => $heroId,
            'action'             => $action,
            'reason'             => $reason,
            'performed_by'       => $userId,
            'performed_by_name'  => $userName,
            'created_at'         => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Retorna o histórico de um herói, do mais recente ao mais antigo.
     */
    public function historyFor(int $heroId): array
    {
        return $this->where('hero_id', $heroId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
