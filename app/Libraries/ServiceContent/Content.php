<?php

namespace App\Libraries\ServiceContent;

use InvalidArgumentException;

class Content
{
    public static function text($value, int $max, string $field, bool $required = true): string
    {
        if (! is_string($value) || ($required && trim($value) === '') || mb_strlen($value) > $max || $value !== strip_tags($value)) {
            throw new InvalidArgumentException('Revise o campo ' . $field . ': texto obrigatório, sem HTML, dentro do limite.');
        }
        return trim($value);
    }

    public static function validate(array $data): array
    {
        $result = [];
        foreach (['title' => 180, 'h1' => 200, 'introduction' => 2500, 'meta_description' => 300, 'whatsapp_message' => 700] as $field => $max) {
            $result[$field] = self::text($data[$field] ?? null, $max, $field);
        }
        $result['sections'] = [];
        $result['keywords'] = [];
        if (! is_array($data['keywords'] ?? [])) throw new InvalidArgumentException('Palavras-chave inválidas.');
        foreach (array_slice($data['keywords'] ?? [], 0, 15) as $keyword) $result['keywords'][] = self::text($keyword, 120, 'palavra-chave');
        $result['search_intent'] = self::text($data['search_intent'] ?? '', 400, 'intenção de busca', false);
        $result['cta_label'] = self::text($data['cta_label'] ?? 'Solicitar orçamento pelo WhatsApp', 90, 'chamada para contato');
        if (! is_array($data['sections'] ?? null) || count($data['sections']) < 1 || count($data['sections']) > 10) {
            throw new InvalidArgumentException('Informe entre 1 e 10 seções.');
        }
        foreach ($data['sections'] as $section) {
            if (! is_array($section)) throw new InvalidArgumentException('Seção inválida.');
            $result['sections'][] = ['heading' => self::text($section['heading'] ?? null, 180, 'título da seção'), 'body' => self::text($section['body'] ?? null, 6000, 'texto da seção')];
        }
        $result['faq'] = [];
        if (! is_array($data['faq'] ?? []) || count($data['faq'] ?? []) > 8) throw new InvalidArgumentException('Máximo de 8 perguntas.');
        foreach ($data['faq'] ?? [] as $faq) {
            $result['faq'][] = ['question' => self::text($faq['question'] ?? null, 250, 'pergunta'), 'answer' => self::text($faq['answer'] ?? null, 1800, 'resposta')];
        }
        $result['photo_ids'] = array_values(array_unique(array_map('intval', array_slice((array) ($data['photo_ids'] ?? []), 0, 12))));
        $result['fact_warnings'] = [];
        foreach (array_slice((array) ($data['fact_warnings'] ?? []), 0, 15) as $warning) $result['fact_warnings'][] = self::text($warning, 500, 'aviso');
        return $result;
    }

    public static function slug(string $text): string
    {
        helper('text');
        $slug = mb_strtolower(convert_accented_characters($text));
        $slug = trim(preg_replace('/[^a-z0-9]+/', '-', $slug), '-');
        if ($slug === '' || strlen($slug) > 150) throw new InvalidArgumentException('Endereço inválido ou longo demais.');
        return $slug;
    }

    public static function whatsapp(string $number, string $message, string $url): string
    {
        if (! preg_match('/^[1-9][0-9]{9,14}$/', $number)) throw new InvalidArgumentException('Confirme o WhatsApp com DDI, somente números.');
        return 'https://wa.me/' . $number . '?text=' . rawurlencode($message . "\n" . $url);
    }

    public static function encode(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }
}
