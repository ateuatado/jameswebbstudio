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

            $ua    = $this->request->getUserAgent();
            $rawUa = $this->request->getServer('HTTP_USER_AGENT') ?? '';

            // Detect Bots & Crawlers (Facebook/Instagram/WhatsApp preview bots, Google, etc.)
            $isBot = $ua->isRobot() || (bool) preg_match('/(facebookexternalhit|meta-externalagent|facebot|whatsapp|twitterbot|telegrambot|linkedinbot|slackbot|discordbot|googlebot|bingbot|bytespider|tiktok|petalbot|semrush|ahrefs)/i', $rawUa);

            if ($isBot) {
                $deviceType = 'bot';
            } elseif ($ua->isMobile() || preg_match('/(android|iphone|ipad|ipod|mobile)/i', $rawUa)) {
                $deviceType = 'mobile';
            } else {
                $deviceType = 'desktop';
            }

            // Detect Browser / In-App Browser
            $browserName = $ua->getBrowser() ?: null;
            if (preg_match('/instagram/i', $rawUa)) {
                $browserName = 'Instagram App';
            } elseif (preg_match('/(FBAV|FBAN)/i', $rawUa)) {
                $browserName = 'Facebook App';
            } elseif (preg_match('/WhatsApp/i', $rawUa)) {
                $browserName = 'WhatsApp';
            } elseif ($isBot) {
                $browserName = 'Crawler / Bot';
            }

            $hitModel = new TrackingHitModel();
            $hitModel->insert([
                'tracking_link_id' => $link->id,
                'ip_address'       => (new GeoIpService())->anonymize($ip),
                'country'          => $geo['country'] ?? null,
                'region'           => $geo['region'] ?? null,
                'city'             => $geo['city'] ?? null,
                'device_type'      => $deviceType,
                'os'               => $ua->getPlatform() ?: null,
                'browser'          => $browserName,
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
