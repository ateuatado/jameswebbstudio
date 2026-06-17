<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ContractSectionSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        $sections = [
            [
                'title'         => 'DAS PARTES',
                'content'       => "CONTRATADO: {contratado_nome}, CPF: {contratado_cpf}, {contratado_estado_civil}, com estúdio em {contratado_endereco}.\n\nCONTRATANTE: {nome_cliente}, CPF: {cpf_cliente}, RG: {rg_cliente}, {estado_civil}, residente em {endereco_completo}, e-mail: {email}, telefone: {telefone}.",
                'display_order' => 10,
            ],
            [
                'title'         => 'DO OBJETO',
                'content'       => "Prestação de serviços fotográficos conforme pacote {nome_pacote}, incluindo sessão fotográfica com duração máxima de 2 (duas) horas no estúdio do CONTRATADO e {qtd_fotos} fotografias tratadas digitalmente.\n\nParágrafo 1º: O tratamento digital abrange correção de cor, exposição, contraste e limpeza básica de pele. Manipulações avançadas de imagem (como alteração corporal, troca de fundos ou remoção de objetos complexos) não estão inclusas e, se solicitadas, serão orçadas à parte.\n\nParágrafo 2º: Caso a sessão exceda o tempo estipulado por solicitação do CONTRATANTE, será cobrada hora adicional no valor de R\$ 150,00 (cento e cinquenta reais).",
                'display_order' => 20,
            ],
            [
                'title'         => 'DO VALOR E PAGAMENTO',
                'content'       => "Valor total de {valor} ({valor_extenso}), pago via {forma_pagamento} no ato da contratação.\n\nParágrafo único: Fotos adicionais além do pacote serão cobradas a {valor_foto_extra} cada, mediante aprovação prévia do CONTRATANTE.",
                'display_order' => 30,
            ],
            [
                'title'         => 'DA DATA E LOCAL',
                'content'       => "A sessão será realizada na data agendada entre as partes, no estúdio do CONTRATADO ou em locação externa previamente acordada.\n\nParágrafo único: Em caso de locação externa escolhida pelo CONTRATANTE, todas as despesas com deslocamento, alimentação, ingressos e taxas de autorização do espaço serão de inteira responsabilidade e custeio do CONTRATANTE.",
                'display_order' => 40,
            ],
            [
                'title'         => 'DA ENTREGA',
                'content'       => "O CONTRATADO enviará uma galeria prévia em baixa resolução em até 5 (cinco) dias úteis após a sessão, para seleção pelo CONTRATANTE.\n\nParágrafo 1º: As fotografias finais serão entregues tratadas em alta resolução em até 15 (quinze) dias úteis, contados a partir da data em que o CONTRATANTE enviar sua seleção final.\n\nParágrafo 2º: Arquivos RAW não fazem parte da entrega e permanecem sob propriedade exclusiva do CONTRATADO.\n\nParágrafo 3º: O CONTRATANTE terá acesso à galeria por 30 (trinta) dias para download.",
                'display_order' => 50,
            ],
            [
                'title'         => 'DOS DIREITOS AUTORAIS',
                'content'       => "Em conformidade com a Lei 9.610/1998, o CONTRATADO é titular dos direitos autorais sobre todas as fotografias.\n\nParágrafo 1º: O CONTRATANTE recebe licença não exclusiva para uso pessoal e profissional.\n\nParágrafo 2º: Vedada revenda ou sublicenciamento sem autorização escrita.\n\nParágrafo 3º: Uso público deve manter crédito ao fotógrafo.",
                'display_order' => 60,
            ],
            [
                'title'         => 'DO USO DE IMAGEM PELO CONTRATADO',
                'content'       => "O CONTRATANTE {autorizacao_imagem} o uso de suas imagens pelo CONTRATADO para portfólio, divulgação e marketing.\n\nParágrafo único: A autorização poderá ser revogada mediante comunicação escrita, aplicando-se apenas a novas publicações. O CONTRATADO não será obrigado a recolher materiais físicos já impressos ou remover campanhas publicitárias que já estejam em veiculação no momento da solicitação.",
                'display_order' => 70,
            ],
            [
                'title'         => 'DO CANCELAMENTO E REAGENDAMENTO',
                'content'       => "a) Cancelamento com mais de 7 (sete) dias de antecedência: reembolso integral.\n\nb) Cancelamento com menos de 7 (sete) dias: retenção de 50% do valor pago a título de multa compensatória pela reserva de agenda, bloqueio da data para outros clientes e custos administrativos.\n\nc) Reagendamento permitido até 2 (duas) vezes, sem custo, com 48h de antecedência.\n\nd) Cancelamento pelo CONTRATADO: reembolso integral.",
                'display_order' => 80,
            ],
            [
                'title'         => 'DA AUSÊNCIA (NO-SHOW)',
                'content'       => "O não comparecimento sem comunicação prévia de 24 (vinte e quatro) horas acarreta a perda integral dos valores já pagos, configurando no-show.\n\nParágrafo único: Caso deseje reagendar, incidirá taxa administrativa de 20% sobre o valor do pacote.",
                'display_order' => 90,
            ],
            [
                'title'         => 'DA FORÇA MAIOR',
                'content'       => "Em caso de impossibilidade por força maior (catástrofes naturais, pandemias, falecimento ou enfermidade grave de qualquer das partes), a sessão será reagendada sem custo adicional.",
                'display_order' => 100,
            ],
            [
                'title'         => 'DA PROTEÇÃO DE DADOS (LGPD)',
                'content'       => "Dados pessoais tratados conforme Lei 13.709/2018, utilizados exclusivamente para execução do contrato, comunicação e documentos fiscais. Dados não serão compartilhados com terceiros sem consentimento.",
                'display_order' => 110,
            ],
            [
                'title'         => 'DO FORO',
                'content'       => "As partes elegem o Foro da Comarca de São Paulo/SP para dirimir quaisquer controvérsias oriundas do presente contrato, com exclusão de qualquer outro, por mais privilegiado que seja.",
                'display_order' => 120,
            ],
        ];

        foreach ($sections as $s) {
            $s['is_active']  = 1;
            $s['created_at'] = date('Y-m-d H:i:s');
            $s['updated_at'] = date('Y-m-d H:i:s');
            $db->table('contract_sections')->insert($s);
        }
    }
}
