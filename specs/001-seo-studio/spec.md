# SEO — James Webb Studio

Data: 23/10/2026. Status: implementação local parcial, aguardando validação e publicação.

## Objetivo e contexto
Gerar contatos qualificados pela busca orgânica em https://jameswebbstudio.com.br/. Hero é projeto anterior ainda ativo: não redirecionar seu domínio inteiro sem mapear conteúdo e equivalências.
Auditoria baseada em código local. A ferramenta não conseguiu consultar produção; disponibilidade, indexação, volume de buscas e desempenho real não foram confirmados. Não prometer ranking ou crescimento percentual.

## US1 — Descoberta e publicação (P1)
Visitantes e robôs devem encontrar home, investimento, ensaios publicados e acervo por links HTML. /sitemap.xml deve ser dinâmico, sem arquivo físico concorrente, com /investimento em vez de /pacotes e apenas conteúdo público publicado. robots.txt aponta ao domínio oficial. Ensaio publicado responde 200; inexistente/despublicado responde 404. Produção deve configurar app.baseURL com o domínio oficial; ambientes podem sobrescrever o padrão.

## US2 — Metadados e indexação (P1)
Home, investimento, ensaios e acervo têm títulos/descrições pertinentes. Layout permite canonical e robots por página. Agendamento reutiliza conteúdo do ensaio e recebe noindex, follow, mantendo o ensaio como página principal. Rotas selecionadas de administração, portal, cliente, autenticação, cortesias, convites e retorno de pagamento recebem X-Robots-Tag. Noindex não substitui autenticação; novas rotas do Shield precisam de revisão.
Galeria continua como unidade canônica: parâmetros imagem/compartilhar preservam compartilhamento e consolidam busca na galeria. URLs individuais por fotografia exigem decisão editorial e migração próprias.

## US3 — Identidade e confiança (P1)
Dados estruturados representam LocalBusiness, sem PhotographAction como tipo da mesma entidade. areaServed pertence ao negócio. Remover avaliação fixa sem evidência. Confirmar endereço, telefone, horários, coordenadas e redes sociais antes de publicar. Centralizar dados e apresentar contato visível após confirmação do responsável.

## US4 — Conteúdo de contratação (P2)
Confirmar serviços e demanda antes de criar páginas de retrato profissional, branding pessoal, atletas ou artistas. Cada página terá conteúdo próprio: público, proposta, portfólio pertinente, processo, entrega, dúvidas reais e CTA. Ensaios terão narrativa validada e links ao serviço. Não inventar casos, avaliações ou promessas; não criar páginas repetidas por bairro. Distinguir caminhos de contratar ensaio e adquirir fotografia autoral.

## US5 — Experiência e imagens (P2)
Primeira imagem do carrossel tem prioridade; demais podem carregar sob demanda. Planejar derivados responsivos e dimensões reais preservando original privado, proporção e qualidade. Não inventar dimensões. Medir peso, LCP, INP e CLS antes/depois; investigar consultas por pacote e geração social durante a visita antes de mudanças maiores.

## Dependências e limites
Necessários Search Console (consultas/páginas até 16 meses, indexação, sitemap, inspeção de URLs), link do Perfil da Empresa, confirmação editorial e ambiente de homologação/publicação. Fora desta primeira entrega: deploy, alterações no Hero, novas páginas sem conteúdo confirmado, pipeline de imagens e instrumentação sem definição de eventos/consentimento.

## Referências
- https://developers.google.com/search/docs/crawling-indexing/sitemaps/overview
- https://developers.google.com/search/docs/appearance/structured-data/local-business
