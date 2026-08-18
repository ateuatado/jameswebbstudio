<?php

namespace App\Libraries;

class GeoIpService
{
    /**
     * Resolve geolocation from IP address using ip-api.com (free, no key needed).
     * Returns array with country, region, city — or empty array on failure.
     */
    public function lookup(string $ip): array
    {
        // Skip private/loopback IPs
        if (in_array($ip, ['127.0.0.1', '::1'], true) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return [];
        }

        $url     = "http://ip-api.com/json/{$ip}?fields=country,regionName,city&lang=pt-BR";
        $context = stream_context_create(['http' => ['timeout' => 1]]);

        try {
            $json = @file_get_contents($url, false, $context);
            if ($json === false) {
                return [];
            }
            $data = json_decode($json, true);
            if (!is_array($data)) {
                return [];
            }
            return [
                'country' => $data['country'] ?? null,
                'region'  => $data['regionName'] ?? null,
                'city'    => $data['city'] ?? null,
            ];
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Anonymize IP by zeroing the last octet (IPv4) for LGPD compliance.
     */
    public function anonymize(string $ip): string
    {
        if (str_contains($ip, ':')) {
            // IPv6 — keep first 4 groups
            $parts = explode(':', $ip);
            return implode(':', array_slice($parts, 0, 4)) . '::0';
        }
        $parts    = explode('.', $ip);
        $parts[3] = '0';
        return implode('.', $parts);
    }
}
