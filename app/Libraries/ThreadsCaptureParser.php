<?php

namespace App\Libraries;

class ThreadsCaptureParser
{
    public function parse(string $raw): array
    {
        $normalized = preg_replace('/\r\n?/', "\n", trim($raw));
        $username = '';
        $profileUrl = '';
        $postUrl = '';
        $publishedRelative = '';

        if (preg_match('/\[\*\*([^*]+)\*\*\]\((https?:\/\/[^)]+)\)/u', $normalized, $match)) {
            $username = trim($match[1]);
            $profileUrl = trim($match[2]);
        }
        if (preg_match_all('/\[([^\]]+)\]\((https?:\/\/[^)]+)\)/u', $normalized, $links, PREG_SET_ORDER)) {
            foreach ($links as $link) {
                if (str_contains($link[2], '/post/')) {
                    $publishedRelative = trim($link[1]);
                    $postUrl = trim($link[2]);
                    break;
                }
            }
        }

        $lines = array_values(array_filter(array_map('trim', explode("\n", $normalized)), static fn ($line) => $line !== ''));
        $headerLines = 0;
        if (!$username && !empty($lines[0]) && preg_match('/^@?([A-Za-z0-9._-]{2,120})$/', $lines[0], $plainUser)) {
            $username = $plainUser[1];
            $profileUrl = 'https://www.threads.com/@' . rawurlencode($username);
            $headerLines = 1;
        }
        if (!$publishedRelative && isset($lines[$headerLines]) && preg_match('/^(agora|\d+\s*(s|m|min|h|d|w|sem|semanas?|mes(?:es)?|hora(?:s)?|dia(?:s)?))$/iu', $lines[$headerLines])) {
            $publishedRelative = $lines[$headerLines];
            $headerLines++;
        }

        $hashtags = [];
        if (preg_match_all('/(?:^|\s)#([\p{L}\p{N}_-]+)/u', $normalized, $tagMatches)) {
            $hashtags = array_values(array_unique(array_map('strtolower', $tagMatches[1])));
        }

        $body = $normalized;
        if (preg_match('/^More\s*$/mi', $body, $more, PREG_OFFSET_CAPTURE)) {
            $body = substr($body, $more[0][1] + strlen($more[0][0]));
        } elseif ($headerLines > 0) {
            $body = implode("\n", array_slice($lines, $headerLines));
        }
        $body = preg_split('/^\s*(?:Like|Reply|Repost|Share|Follow)\s*$/mi', $body, 2)[0] ?? $body;
        $body = preg_replace('/(?:^|\s)#[\p{L}\p{N}_-]+/u', ' ', $body);
        $body = trim(preg_replace('/[ \t]+/', ' ', $body));

        $lowerTags = implode(' ', $hashtags);
        $isProfessional = (bool) preg_match('/tatuadora|tattooartist|designer|nail|psic[oó]loga|fot[oó]grafa|m[eé]dica|dra\.|profissional|empreendedora/i', $lowerTags . ' ' . $body);
        $isAesthetic = (bool) preg_match('/altgirl|alternative|goth|g[oó]tica|dark|metal|punk|alternativeaesthetic/i', $lowerTags . ' ' . $body);
        $category = $isProfessional ? 'profissão' : ($isAesthetic ? 'afinidade estética' : 'outro');

        return [
            'username' => ltrim($username, '@'),
            'profile_url' => $profileUrl,
            'post_url' => $postUrl ?: ($profileUrl ?: null),
            'published_relative' => $publishedRelative,
            'hashtags' => $hashtags,
            'original_text' => $body,
            'context_category' => $category,
            'priority' => $isProfessional ? 'high' : ($isAesthetic ? 'medium' : 'low'),
        ];
    }
}
