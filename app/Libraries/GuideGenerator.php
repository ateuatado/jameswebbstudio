<?php

namespace App\Libraries;

use App\Models\GuideSectionModel;
use App\Models\CategoryModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class GuideGenerator
{
    /**
     * Gera o PDF do guia pré-ensaio personalizado.
     */
    public function generate(string $clientName, string $clientEmail, ?int $categoryId = null, string $shootDate = ''): string
    {
        $model    = new GuideSectionModel();
        $sections = $model->getForCategory($categoryId);

        // Tipo de ensaio
        $shootType = '';
        if ($categoryId) {
            $catModel  = new CategoryModel();
            $cat       = $catModel->find($categoryId);
            $shootType = $cat ? $cat->name : '';
        }

        if (empty($shootDate)) {
            $shootDate = date('d/m/Y');
        }

        // Função para formatar conteúdo: substitui emojis por chars compatíveis com DOMPDF
        $formatContent = function (string $text): string {
            // Sanitiza
            $text = esc($text);

            // Substitui emojis por marcadores HTML estilizados
            $text = str_replace(
                ['✅', '&#10004;'],
                ['<span class="item-yes">+</span>'],
                $text
            );
            $text = str_replace(
                ['❌', '&#10060;'],
                ['<span class="item-no">-</span>'],
                $text
            );
            $text = str_replace(
                ['•', '&#8226;'],
                ['<span class="item-bullet">&bull;</span>'],
                $text
            );

            // Remove qualquer emoji remanescente (4-byte UTF-8)
            $text = preg_replace('/[\x{1F000}-\x{1FFFF}]/u', '', $text);
            $text = preg_replace('/[\x{2600}-\x{27BF}]/u', '', $text);
            $text = preg_replace('/[\x{FE00}-\x{FE0F}]/u', '', $text);

            // Colapsa múltiplas linhas em branco em uma só
            $text = preg_replace('/\n{3,}/', "\n\n", $text);
            // Converte \n\n em um espaço pequeno (não dois <br>)
            $text = str_replace("\n\n", "\n", $text);

            // Converte quebras de linha em <br>
            $text = nl2br($text);

            return $text;
        };

        // Renderiza o HTML do template
        $html = view('pdf/guide', [
            'clientName'    => $clientName,
            'clientEmail'   => $clientEmail,
            'shootType'     => $shootType,
            'shootDate'     => $shootDate,
            'sections'      => $sections,
            'formatContent' => $formatContent,
        ]);

        // Gera PDF via DOMPDF
        $options = new Options();
        $options->set('defaultFont', 'Inter');
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isFontSubsettingEnabled', true);

        $dompdf = new Dompdf($options);

        // Registra fonte Inter (moderna, a mesma do site)
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
}
