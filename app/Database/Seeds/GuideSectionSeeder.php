<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GuideSectionSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // Busca IDs das categorias para vincular seções por nicho
        $cats = $db->table('categories')->select('id, name')->where('is_active', 1)->get()->getResultArray();
        $catMap = [];
        foreach ($cats as $c) {
            $catMap[mb_strtolower($c['name'])] = $c['id'];
        }

        $brandingId  = $catMap['branding pessoal'] ?? $catMap['retrato branding pessoal'] ?? null;
        $esportivoId = $catMap['performance esportiva'] ?? $catMap['esportivo'] ?? null;

        $sections = [
            // ── UNIVERSAIS ──
            [
                'category_id'   => null,
                'title'         => 'Bem-vindo ao seu ensaio',
                'content'       => "Fala! Que bom que você decidiu registrar esse momento.\n\nEste guia existe para que a gente aproveite cada segundo no estúdio. Leia com calma — não é longo — e qualquer dúvida, me chama.\n\nVamos juntos!",
                'display_order' => 10,
            ],
            [
                'category_id'   => null,
                'title'         => 'Figurino — A regra de ouro',
                'content'       => "Cores sólidas. Sempre. Essa é a dica mais importante.\n\n✅ Amarelo, ocre, bege, marrom, café, capuchino\n✅ Tons de verde, rosa, terracota, vinho\n✅ Preto, branco, off-white — clássicos que nunca falham\n\n❌ Estampas pequenas (xadrez miúdo, listras finas) — tremem na câmera\n❌ Estampas grandes e chamativas — competem com o rosto\n❌ Cores neon ou fluorescentes — refletem na pele\n\nDica de ouro: Monte looks com cores da mesma família (ex: bege + marrom + creme). Isso cria harmonia visual sem esforço.",
                'display_order' => 20,
            ],
            [
                'category_id'   => null,
                'title'         => 'Cuide dos detalhes invisíveis',
                'content'       => "A câmera vê o que o olho ignora:\n\n• Roupas passadas e limpas — sem fios soltos, manchas ou marcas de dobra (relaxa, tenho um vaporizador no estúdio pra emergências)\n• Etiquetas aparecendo? Tira ou dobra pra dentro\n• Costuras desfiando? Melhor trocar a peça\n• Meias e sapatos importam — mesmo que pareça que não vão aparecer",
                'display_order' => 30,
            ],
            [
                'category_id'   => null,
                'title'         => 'Cabelo, barba e cuidados pessoais',
                'content'       => "• Cabelo: venha como você se sente bem. Se costuma arrumar, arrume. Se é natural, venha natural\n• Barba/bigode: aparados e alinhados (a não ser que o estilo seja justamente o contrário)\n• Sobrancelhas e cílios: se você cuida habitualmente, mantenha em dia\n• Unhas: se vão aparecer (e quase sempre aparecem), limpas e cuidadas\n• Pele: nada elaborado. Hidratante básico. Menos é mais",
                'display_order' => 40,
            ],
            [
                'category_id'   => null,
                'title'         => 'Maquiagem',
                'content'       => "Se o seu pacote inclui maquiador profissional, ele cuida de tudo.\n\nSe não inclui: venha como se sente bem. Sério. A melhor maquiagem para foto é aquela que te faz olhar no espelho e gostar do que vê. Você vai sorrir diferente, vai posar diferente. E isso vale mais que qualquer técnica.",
                'display_order' => 50,
            ],
            [
                'category_id'   => null,
                'title'         => 'O que trazer',
                'content'       => "• Todos os acessórios que puder: relógios, anéis, colares, brincos, óculos, chapéus, bonés\n• Objetos pessoais que contem sua história: troféu, instrumento, ferramenta de trabalho, livro\n• Cada acessório é uma possibilidade de contar algo sobre você\n• Traga as roupas em cabides ou bem dobradas (evite amontoar na mochila)",
                'display_order' => 60,
            ],
            [
                'category_id'   => null,
                'title'         => 'No dia do ensaio',
                'content'       => "• Chegue no horário. Nem antes, nem depois. Pontualidade é respeito mútuo\n• Coma normalmente — se sinta bem, sem exageros\n• Deixe o celular no silencioso — esse tempo é seu\n• O estúdio fica na Lapa, num bairro encantador que parou no tempo\n• Tem estacionamento na rua e Wi-Fi disponível no estúdio",
                'display_order' => 70,
            ],
            [
                'category_id'   => null,
                'title'         => 'Relaxe',
                'content'       => "Eu sei que muita gente fica nervoso antes de um ensaio. Normal.\n\nMas meu trabalho é justamente te guiar. Você não precisa saber posar, não precisa ser modelo. Eu vou te dirigir em cada foto.\n\nSeu único trabalho é curtir o processo.",
                'display_order' => 80,
            ],

            // ── BRANDING PESSOAL ──
            [
                'category_id'   => $brandingId,
                'title'         => 'Especial: Branding Pessoal',
                'content'       => "• Priorize peças que representem como você quer ser percebido: autoridade? Acessível? Criativo?\n• Blazer + camiseta básica = equilíbrio entre profissional e humano\n• Traga o logo da sua empresa (adesivo, cartão, laptop com a tela da marca)\n• Se usa uniforme no trabalho, traga também — pode render fotos autênticas\n• LinkedIn, site, cartão de visitas — pense onde as fotos vão ser usadas",
                'display_order' => 10,
            ],

            // ── PERFORMANCE ESPORTIVA ──
            [
                'category_id'   => $esportivoId,
                'title'         => 'Especial: Performance Esportiva',
                'content'       => "• Traga TODOS os equipamentos: luvas, faixas, raquete, bola, tênis, sapatilha\n• Roupas de treino limpas e bem cuidadas (a câmera amplia rasgos e manchas)\n• Se compete com uniforme de equipe, traga ele\n• Peças justas fotografam melhor que peças largas — mostram a musculatura\n• Se usa bandagem/tape, traga feito ou material para fazer no estúdio",
                'display_order' => 10,
            ],
        ];

        foreach ($sections as $s) {
            $s['is_active']  = 1;
            $s['created_at'] = date('Y-m-d H:i:s');
            $s['updated_at'] = date('Y-m-d H:i:s');
            $db->table('guide_sections')->insert($s);
        }
    }
}
