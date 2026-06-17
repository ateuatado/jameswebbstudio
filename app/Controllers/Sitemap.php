<?php

namespace App\Controllers;

use App\Models\HeroModel;

class Sitemap extends BaseController
{
    public function index()
    {
        $heroModel = new HeroModel();
        $heroes = $heroModel->where('is_active', 1)->findAll();

        $urls = [
            ['loc' => site_url('/'),       'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => site_url('pacotes'), 'priority' => '0.9', 'changefreq' => 'weekly'],
        ];

        foreach ($heroes as $hero) {
            $urls[] = [
                'loc'        => site_url($hero->slug),
                'priority'   => '0.8',
                'changefreq' => 'monthly',
                'lastmod'    => date('Y-m-d', strtotime($hero->updated_at ?? $hero->created_at)),
            ];
        }

        $this->response->setContentType('application/xml');
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . esc($url['loc']) . "</loc>\n";
            if (!empty($url['lastmod'])) {
                $xml .= "    <lastmod>" . $url['lastmod'] . "</lastmod>\n";
            }
            $xml .= "    <changefreq>" . $url['changefreq'] . "</changefreq>\n";
            $xml .= "    <priority>" . $url['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';
        return $this->response->setBody($xml);
    }
}
