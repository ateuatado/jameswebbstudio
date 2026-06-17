<?php

namespace App\Libraries;

use App\Models\ContractSectionModel;
use App\Models\PackageModel;
use App\Models\StudioSettingModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ContractGenerator
{
    /**
     * Generates a personalized contract PDF.
     * $orderData should contain: buyer_name, buyer_email, buyer_phone, cpf, marital_status, address, city, state, zip_code, image_usage_authorized, package_id, amount, created_at
     */
    public function generate(object $orderData): string
    {
        // ── Cláusulas do contrato ─────────────────────────────────────
        try {
            $model = new ContractSectionModel();
            $sections = $model->getActive();
        } catch (\Throwable $e) {
            $sections = [];
        }

        // ── Dados do estúdio (contratado) ─────────────────────────────
        try {
            $studio = (new StudioSettingModel())->getAll();
        } catch (\Throwable $e) {
            $studio = [];
        }
        $studioAddress = implode(', ', array_filter([
            $studio['studio_address'] ?? '',
            $studio['studio_neighborhood'] ?? '',
            $studio['studio_city'] ?? '',
            ($studio['studio_state'] ?? '') ? strtoupper($studio['studio_state']) : '',
        ]));

        // Get package info
        $packageName = 'Ensaio Fotografico';
        $includedPhotos = '';
        $extraPhotoPrice = '';
        if (!empty($orderData->package_id)) {
            $pkg = (new PackageModel())->find($orderData->package_id);
            if ($pkg) {
                $packageName = $pkg->name;
                $includedPhotos = $pkg->included_photos ?? '';
                $extraPhotoPrice = $pkg->extra_photo_price ? 'R$ ' . number_format($pkg->extra_photo_price, 0, ',', '.') : 'a combinar';
            }
        }

        // Build client address
        $addressParts = array_filter([
            $orderData->address ?? '',
            $orderData->city ?? '',
            ($orderData->state ?? '') ? strtoupper($orderData->state) : '',
            $orderData->zip_code ?? '',
        ]);
        $fullAddress = implode(', ', $addressParts) ?: 'a ser informado';

        // Placeholder replacements
        $replacements = [
            // Contratado (fotógrafo)
            '{contratado_nome}'      => $studio['owner_full_name'] ?? 'Marco Santo Fotografia',
            '{contratado_cpf}'       => $studio['owner_cpf'] ?? '',
            '{contratado_estado_civil}' => $studio['owner_marital_status'] ?? '',
            '{contratado_endereco}'  => $studioAddress,
            '{contratado_email}'     => $studio['studio_email'] ?? '',
            '{contratado_telefone}'  => $studio['studio_phone'] ?? '',
            '{nome_estudio}'         => $studio['studio_name'] ?? 'Marco Santo Fotografia',

            // Contratante (cliente)
            '{nome_cliente}' => $orderData->buyer_name ?? 'A ser informado',
            '{cpf_cliente}' => $orderData->cpf ?? '___.___.___-__',
            '{rg_cliente}' => $orderData->rg ?? 'a ser informado',
            '{estado_civil}' => $orderData->marital_status ?? 'a ser informado',
            '{endereco_completo}' => $fullAddress,
            '{email}' => $orderData->buyer_email ?? '',
            '{telefone}' => $orderData->buyer_phone ?? '',
            '{nome_pacote}' => $packageName,
            '{valor}' => 'R$ ' . number_format((float)($orderData->amount ?? 0), 2, ',', '.'),
            '{valor_extenso}' => $this->valorPorExtenso((float)($orderData->amount ?? 0)),
            '{qtd_fotos}' => $includedPhotos,
            '{valor_foto_extra}' => $extraPhotoPrice,
            '{data_contratacao}' => !empty($orderData->created_at) ? date('d/m/Y', strtotime($orderData->created_at)) : date('d/m/Y'),
            '{forma_pagamento}' => 'pagamento online (PIX ou cartao)',
            '{autorizacao_imagem}' => ($orderData->image_usage_authorized ?? null) ? 'AUTORIZA' : 'NAO AUTORIZA',
            '{numero_contrato}' => str_pad($orderData->id ?? 0, 6, '0', STR_PAD_LEFT),
        ];

        // Process sections - replace placeholders
        $processedSections = [];
        foreach ($sections as $s) {
            $processed = clone $s;
            $processed->content = str_replace(array_keys($replacements), array_values($replacements), $s->content);
            $processedSections[] = $processed;
        }

        $mesesPT = ['janeiro','fevereiro','março','abril','maio','junho','julho','agosto','setembro','outubro','novembro','dezembro'];
        $ts = !empty($orderData->created_at) ? strtotime($orderData->created_at) : time();
        $contractDate = date('d', $ts) . ' de ' . $mesesPT[(int)date('n', $ts) - 1] . ' de ' . date('Y', $ts);

        // Format content function - same as guide
        $formatContent = function (string $text): string {
            $text = esc($text);
            // Remove emojis
            $text = preg_replace('/[\x{1F000}-\x{1FFFF}]/u', '', $text);
            $text = preg_replace('/[\x{2600}-\x{27BF}]/u', '', $text);
            $text = preg_replace('/[\x{FE00}-\x{FE0F}]/u', '', $text);
            // Collapse blank lines
            $text = preg_replace('/\n{3,}/', "\n\n", $text);
            $text = str_replace("\n\n", "\n", $text);
            $text = nl2br($text);
            return $text;
        };

        $html = view('pdf/contract', [
            'sections'       => $processedSections,
            'contractDate'   => $contractDate,
            'contractNumber' => $replacements['{numero_contrato}'],
            'clientName'     => $replacements['{nome_cliente}'],
            'clientCpf'      => $replacements['{cpf_cliente}'],
            'studioName'     => $replacements['{nome_estudio}'],
            'ownerName'      => $replacements['{contratado_nome}'],
            'ownerCpf'       => $replacements['{contratado_cpf}'],
            'formatContent'  => $formatContent,
        ]);

        $options = new Options();
        $options->set('defaultFont', 'Inter');
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isFontSubsettingEnabled', true);

        $dompdf = new Dompdf($options);

        // Register Inter font
        $fontDir = WRITEPATH . 'fonts/';
        if (file_exists($fontDir . 'Inter-Regular.ttf')) {
            $fontMetrics = $dompdf->getFontMetrics();
            $fontMetrics->registerFont(
                ['family' => 'Inter', 'style' => 'normal', 'weight' => 'normal'],
                $fontDir . 'Inter-Regular.ttf'
            );
            $fontMetrics->registerFont(
                ['family' => 'Inter', 'style' => 'normal', 'weight' => 'bold'],
                $fontDir . 'Inter-Bold.ttf'
            );
            if (file_exists($fontDir . 'Inter-Italic.ttf')) {
                $fontMetrics->registerFont(
                    ['family' => 'Inter', 'style' => 'italic', 'weight' => 'normal'],
                    $fontDir . 'Inter-Italic.ttf'
                );
            }
        }

        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Converte valor numérico para extenso em português.
     */
    private function valorPorExtenso(float $valor): string
    {
        $inteiro = (int) floor($valor);
        $centavos = (int) round(($valor - $inteiro) * 100);

        $unidades = ['', 'um', 'dois', 'tres', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove',
                     'dez', 'onze', 'doze', 'treze', 'quatorze', 'quinze', 'dezesseis', 'dezessete', 'dezoito', 'dezenove'];
        $dezenas  = ['', '', 'vinte', 'trinta', 'quarenta', 'cinquenta', 'sessenta', 'setenta', 'oitenta', 'noventa'];
        $centenas = ['', 'cento', 'duzentos', 'trezentos', 'quatrocentos', 'quinhentos',
                     'seiscentos', 'setecentos', 'oitocentos', 'novecentos'];

        $extenso = function (int $n) use ($unidades, $dezenas, $centenas): string {
            if ($n === 0) return 'zero';
            if ($n === 100) return 'cem';

            $parts = [];
            if ($n >= 100) {
                $parts[] = $centenas[(int) floor($n / 100)];
                $n %= 100;
            }
            if ($n >= 20) {
                $parts[] = $dezenas[(int) floor($n / 10)];
                $n %= 10;
            }
            if ($n > 0) {
                $parts[] = $unidades[$n];
            }
            return implode(' e ', $parts);
        };

        $partes = [];
        if ($inteiro >= 1000) {
            $milhares = (int) floor($inteiro / 1000);
            $partes[] = ($milhares === 1 ? 'mil' : $extenso($milhares) . ' mil');
            $inteiro %= 1000;
        }
        if ($inteiro > 0) {
            $partes[] = $extenso($inteiro);
        }

        $resultado = implode(' e ', $partes);
        $resultado .= ($valor >= 2 || $valor < 1) ? ' reais' : ' real';

        if ($centavos > 0) {
            $resultado .= ' e ' . $extenso($centavos) . ($centavos > 1 ? ' centavos' : ' centavo');
        }

        return $resultado;
    }
}
