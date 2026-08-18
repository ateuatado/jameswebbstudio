<?php

namespace App\Models;

use CodeIgniter\Model;

class TrackingLinkModel extends Model
{
    protected $table         = 'tracking_links';
    protected $primaryKey    = 'id';
    protected $useTimestamps  = true;
    protected $returnType    = 'object';

    protected $allowedFields = [
        'slug', 'destination_url', 'utm_source', 'utm_medium',
        'utm_campaign', 'utm_content', 'is_active',
    ];

    protected $validationRules = [
        'slug'            => 'required|alpha_dash|max_length[100]|is_unique[tracking_links.slug,id,{id}]',
        'destination_url' => 'required|valid_url_strict|max_length[512]',
    ];

    protected $validationMessages = [
        'slug' => [
            'is_unique' => 'Este slug já está em uso. Escolha outro.',
        ],
    ];

    public function findBySlug(string $slug): ?object
    {
        return $this->where('slug', $slug)->where('is_active', 1)->first();
    }

    public function withHitCount(): array
    {
        return $this->db->table('tracking_links tl')
            ->select('tl.*, COUNT(th.id) as hit_count')
            ->join('tracking_hits th', 'th.tracking_link_id = tl.id', 'left')
            ->groupBy('tl.id')
            ->orderBy('tl.created_at', 'DESC')
            ->get()->getResultObject();
    }
}
