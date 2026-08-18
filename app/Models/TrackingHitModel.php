<?php

namespace App\Models;

use CodeIgniter\Model;

class TrackingHitModel extends Model
{
    protected $table         = 'tracking_hits';
    protected $primaryKey    = 'id';
    protected $useTimestamps = false;
    protected $returnType    = 'object';

    protected $allowedFields = [
        'tracking_link_id', 'ip_address', 'country', 'region',
        'city', 'device_type', 'os', 'browser', 'referer', 'created_at',
    ];

    public function countHitsByPeriod(int $linkId, string $from, string $to): int
    {
        return (int) $this->where('tracking_link_id', $linkId)
            ->where('created_at >=', $from)
            ->where('created_at <=', $to)
            ->countAllResults();
    }

    public function hitsBySource(string $from, string $to, bool $excludeBots = false): array
    {
        $builder = $this->db->table('tracking_hits th')
            ->select('tl.utm_source, COUNT(th.id) as total')
            ->join('tracking_links tl', 'tl.id = th.tracking_link_id')
            ->where('th.created_at >=', $from)
            ->where('th.created_at <=', $to);

        if ($excludeBots) {
            $builder->where('th.device_type !=', 'bot');
        }

        return $builder->groupBy('tl.utm_source')
            ->orderBy('total', 'DESC')
            ->get()->getResultObject();
    }

    public function hitsByDay(string $from, string $to, ?int $linkId = null, bool $excludeBots = false): array
    {
        $builder = $this->db->table('tracking_hits')
            ->select('DATE(created_at) as day, COUNT(id) as total')
            ->where('created_at >=', $from)
            ->where('created_at <=', $to);
        if ($linkId) {
            $builder->where('tracking_link_id', $linkId);
        }
        if ($excludeBots) {
            $builder->where('device_type !=', 'bot');
        }
        return $builder->groupBy('DATE(created_at)')->orderBy('day', 'ASC')->get()->getResultObject();
    }

    public function hitsByCampaign(string $from, string $to, bool $excludeBots = false): array
    {
        $builder = $this->db->table('tracking_hits th')
            ->select('tl.utm_campaign, COUNT(th.id) as total')
            ->join('tracking_links tl', 'tl.id = th.tracking_link_id')
            ->where('th.created_at >=', $from)
            ->where('th.created_at <=', $to);

        if ($excludeBots) {
            $builder->where('th.device_type !=', 'bot');
        }

        return $builder->groupBy('tl.utm_campaign')
            ->orderBy('total', 'DESC')
            ->get()->getResultObject();
    }

    public function hitsByCity(string $from, string $to, int $limit = 10, bool $excludeBots = false): array
    {
        $builder = $this->db->table('tracking_hits')
            ->select('city, country, COUNT(id) as total')
            ->where('created_at >=', $from)
            ->where('created_at <=', $to)
            ->where('city IS NOT NULL');

        if ($excludeBots) {
            $builder->where('device_type !=', 'bot');
        }

        return $builder->groupBy('city, country')
            ->orderBy('total', 'DESC')
            ->limit($limit)
            ->get()->getResultObject();
    }

    public function hitsByDevice(string $from, string $to, bool $excludeBots = false): array
    {
        $builder = $this->db->table('tracking_hits')
            ->select('device_type, COUNT(id) as total')
            ->where('created_at >=', $from)
            ->where('created_at <=', $to);

        if ($excludeBots) {
            $builder->where('device_type !=', 'bot');
        }

        return $builder->groupBy('device_type')
            ->orderBy('total', 'DESC')
            ->get()->getResultObject();
    }

    public function hitsByBrowser(string $from, string $to, ?int $linkId = null): array
    {
        $builder = $this->db->table('tracking_hits')
            ->select('browser, COUNT(id) as total')
            ->where('created_at >=', $from)
            ->where('created_at <=', $to)
            ->where('browser IS NOT NULL');

        if ($linkId) {
            $builder->where('tracking_link_id', $linkId);
        }

        return $builder->groupBy('browser')
            ->orderBy('total', 'DESC')
            ->get()->getResultObject();
    }

    public function totalHits(string $from, string $to, ?int $linkId = null): int
    {
        $builder = $this->where('created_at >=', $from)->where('created_at <=', $to);
        if ($linkId) {
            $builder = $builder->where('tracking_link_id', $linkId);
        }
        return (int) $builder->countAllResults();
    }

    public function uniqueVisitors(string $from, string $to, ?int $linkId = null, bool $excludeBots = false): int
    {
        $builder = $this->db->table('tracking_hits')
            ->select('COUNT(DISTINCT ip_address) as total')
            ->where('created_at >=', $from)
            ->where('created_at <=', $to);

        if ($linkId) {
            $builder->where('tracking_link_id', $linkId);
        }
        if ($excludeBots) {
            $builder->where('device_type !=', 'bot');
        }

        $row = $builder->get()->getRow();
        return (int) ($row->total ?? 0);
    }

    public function botHits(string $from, string $to, ?int $linkId = null): int
    {
        $builder = $this->where('created_at >=', $from)
            ->where('created_at <=', $to)
            ->where('device_type', 'bot');

        if ($linkId) {
            $builder = $builder->where('tracking_link_id', $linkId);
        }
        return (int) $builder->countAllResults();
    }

    public function recentHits(int $limit = 15, ?int $linkId = null): array
    {
        $builder = $this->db->table('tracking_hits th')
            ->select('th.*, tl.slug, tl.utm_source, tl.utm_campaign')
            ->join('tracking_links tl', 'tl.id = th.tracking_link_id', 'left')
            ->orderBy('th.created_at', 'DESC')
            ->limit($limit);

        if ($linkId) {
            $builder->where('th.tracking_link_id', $linkId);
        }

        return $builder->get()->getResultObject();
    }
}
