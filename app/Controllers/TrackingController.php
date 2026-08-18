<?php

namespace App\Controllers;

use App\Libraries\GeoIpService;
use App\Models\TrackingHitModel;
use App\Models\TrackingLinkModel;

class TrackingController extends BaseController
{
    public function redirect(string $slug)
    {
        $linkModel = new TrackingLinkModel();
        $link      = $linkModel->findBySlug($slug);

        if (!$link) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // ── Capture data ───────────────────────────────────────────────────────
        try {
            $ip  = $this->resolveIp();
            $geo = (new GeoIpService())->lookup($ip);

            $ua         = $this->request->getUserAgent();
            $deviceType = $ua->isMobile() ? 'mobile' : ($ua->isRobot() ? 'bot' : 'desktop');

            $hitModel = new TrackingHitModel();
            $hitModel->insert([
                'tracking_link_id' => $link->id,
                'ip_address'       => (new GeoIpService())->anonymize($ip),
                'country'          => $geo['country'] ?? null,
                'region'           => $geo['region'] ?? null,
                'city'             => $geo['city'] ?? null,
                'device_type'      => $deviceType,
                'os'               => $ua->getPlatform() ?: null,
                'browser'          => $ua->getBrowser() ?: null,
                'referer'          => $this->request->getServer('HTTP_REFERER') ?: null,
                'created_at'       => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable) {
            // Silent — never block the redirect
        }

        return redirect()->to($link->destination_url);
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    private function resolveIp(): string
    {
        $forwarded = $this->request->getServer('HTTP_X_FORWARDED_FOR');
        if ($forwarded) {
            return trim(explode(',', $forwarded)[0]);
        }
        return $this->request->getServer('REMOTE_ADDR') ?? '0.0.0.0';
    }
}
