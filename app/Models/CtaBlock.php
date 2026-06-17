<?php

namespace App\Models;

use CodeIgniter\Model;

class CtaBlock extends Model
{
    protected $table         = 'cta_blocks';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['cta_id', 'type', 'content', 'display_order'];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Retorna os blocos de um CTA ordenados, com content já decodificado como array.
     */
    public function blocksForCta(int $ctaId): array
    {
        $rows = $this->where('cta_id', $ctaId)
                     ->orderBy('display_order', 'ASC')
                     ->findAll();

        foreach ($rows as &$row) {
            $row['content'] = is_string($row['content'])
                ? json_decode($row['content'], true) ?? []
                : ($row['content'] ?? []);
        }
        return $rows;
    }

    /**
     * Salva um bloco novo codificando o content em JSON.
     */
    public function saveBlock(int $ctaId, string $type, array $content, int $order = 0): void
    {
        $this->insert([
            'cta_id'        => $ctaId,
            'type'          => $type,
            'content'       => json_encode($content, JSON_UNESCAPED_UNICODE),
            'display_order' => $order,
        ]);
    }

    /**
     * Atualiza content de um bloco existente.
     */
    public function updateBlock(int $id, array $content): void
    {
        $this->update($id, [
            'content' => json_encode($content, JSON_UNESCAPED_UNICODE),
        ]);
    }
}
