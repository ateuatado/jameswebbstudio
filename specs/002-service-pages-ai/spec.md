# Spec 002 — Páginas de serviços com geração assistida por IA

Data: 23/10/2026. Estado: versão inicial implementada e validada localmente; piloto editorial e publicação no VPS pendentes. Ver [operação e limites](quickstart.md).

## Objetivo
Ampliar a apresentação e a descoberta dos serviços reais do James Webb Studio além dos ensaios: fotografia de produtos/still, imóveis, moda e modalidades relacionadas. Permitir criação e edição de páginas em lote com contexto do negócio, IA, portfólio autorizado e contato contextual pelo WhatsApp.

## Alinhamento com o sistema
CodeIgniter 4/PHP, MySQL/MariaDB, Shield, MVC, painel e identidade visual existentes. Nenhuma substituição de framework, checkout ou portais. `services` são componentes comerciais de pacotes, não páginas editoriais: criar entidades próprias. `heroes/photos` continuam ensaios; `photo_works/photo_work_images` continuam obras autorais. Convites Threads continuam privados e noindex. Integrações existentes de agenda, Mercado Pago e AWS não serão alteradas por esta funcionalidade.
`constitution.md` contém placeholders e comentários de exemplo, sem constituição ratificada. Estes não são requisitos vigentes. A implementação deve respeitar regras concretas de spec.md, plan.md e tasks.md, autorização Shield, separação dos módulos, privacidade, originais privados e execução local antes de deploy assistido pelo usuário.

## Requisitos confirmados pelo usuário
- RF01: cadastrar serviços e modalidades reais e criar páginas individuais ou em massa a partir de temas, assuntos e expressões.
- RF02: usar API de IA para interpretar contexto e gerar conteúdo pertinente, não apenas substituir palavras em um texto.
- RF03: apresentar portfólio e CTA para WhatsApp com mensagem contextual por página.
- RF04: integrar ao administrativo, navegação pública e sitemap dinâmico.
- RF05: documentar e implementar incrementalmente, preservando funcionalidades públicas e administrativas existentes.

## Decisões propostas para a primeira versão
- Tema → rascunho → revisão → publicação explícita, inclusive em lote. IA nunca publica sozinha. Uma necessidade pode reunir várias expressões; agrupamento sugerido não exclui entradas sem confirmação.
- Modelos por família: ensaios, produtos, imóveis, moda. Nomes não confirmam automaticamente oferta, prazo ou cobertura.
- Conteúdo estruturado por blocos conhecidos; IA não fornece HTML executável, scripts, SQL, URLs livres nem decisões de autorização.
- IA textual, sem gerar imagens; selecionar mídia existente autorizada ou imagem enviada pelo admin.
- Primeiro piloto com três páginas de serviços diferentes. Lotes iniciais limitados a 25 temas, concorrência 1, limites configuráveis depois de medir custo/capacidade. São padrões propostos, não escolhas já confirmadas do usuário.

## Histórias e aceitação
### US1 — Base de conhecimento (P1)
Administrador registra oferta, modalidades, público, cidade/regiões, diferenciais, processo, entregáveis, contato, restrições e referências de portfólio. Dados têm versão, autor e data. A IA recebe somente contexto aprovado; preços, endereço e horários não devem ser inferidos. Alterar contexto não modifica páginas publicadas automaticamente.
Aceitação: contexto incompleto sinaliza campos faltantes; não gera afirmações comerciais sem base. Nenhuma credencial ou dado privado de cliente compõe o prompt.

### US2 — Cadastro e geração individual (P1)
Admin escolhe família/modalidade, tema, objetivo, expressões relacionadas, mídias e contexto. IA propõe título, H1, introdução, benefícios verificáveis, aplicações, processo, FAQ pertinente, descrição SEO e mensagem WhatsApp. Seções opcionais podem ser omitidas. Usuário edita tudo e vê prévia.
Aceitação: JSON inválido/incompleto vira falha recuperável, sem página pública. Links e fatos não autorizados são rejeitados/sinalizados. Conteúdo válido é salvo como rascunho, com proveniência e versão do contexto/prompt/modelo.

### US3 — Lotes (P1)
Colar uma lista, uma entrada por linha; CSV UTF-8 opcional em etapa posterior. Revisão prévia apresenta temas normalizados, duplicados, agrupamentos sugeridos, dados ausentes e teto de consumo. Após confirmar geração, cada item tem estado e resultado próprio.
Aceitação: requisições HTTP apenas enfileiram; worker CLI processa. Timeout/429/falha em um item não perde os demais. Retentativas limitadas e idempotentes não duplicam páginas. Cancelamento impede itens não iniciados; chamada já enviada pode gerar custo e seu resultado não será publicado. Relançar um lote existente exige chave nova ou reutiliza itens concluídos.

### US4 — Revisão e versões (P1)
Estados: draft, in_review, published, archived; falhas de geração pertencem ao job. Revisão compara versão proposta e atual. Publicação pode selecionar várias páginas com aprovação individual registrada e validações aprovadas.
Aceitação: um editor não sobrescreve silenciosamente a revisão de outro; regeneração gera nova versão, preservando edições e a versão pública. Falha da IA não afeta páginas já publicadas. Restaurar uma revisão passa por nova publicação explícita.

### US5 — Página pública e descoberta (P1)
Rotas sob /servicos, sem alterar /investimento, /fotos ou catch-all de ensaios. URLs estáveis e hierarquia limitada a serviço/modalidade. Conteúdo renderizado no servidor, H1 coerente, title/description/canonical, links HTML, breadcrumbs e mídia responsiva. Sitemap lista somente publicadas, canônicas e indexáveis. robots.txt mantém uma referência ao sitemap; não ganha linha por página. Prévia autenticada, noindex e fora de caches públicos.
Aceitação: publicada retorna 200; rascunho inexistente para visitante retorna 404; prévia exige permissão. Alterar texto não muda slug. Troca explícita de slug cria 301 interno sem loop/cadeia; arquivo sem substituta retorna 410 para URL antes publicada, enquanto nunca publicada retorna 404. Índice/hubs sem conteúdo permanecem fora do sitemap até estarem prontos.

### US6 — WhatsApp (P1)
Número confirmado em configuração do estúdio, apenas dígitos com código internacional; mensagem codificada com contexto do serviço e URL canônica. Link wa.me funcional sem login no site. Pode ser o mesmo número em todas as páginas.
Aceitação: sem número válido, bloqueia publicação com explicação. CTA é link real e funciona sem JavaScript. Não envia mensagens automaticamente. Não inclui nome do visitante nem informação privada. Clique é métrica de contato, nunca prova de conversa/venda.

### US7 — Operação e resultados (P2)
Dashboard de páginas, lotes, erros, consumo, versão pública, última revisão e cliques agregados. Impressões/cliques orgânicos inicialmente por exportação do Search Console; não requer integração OAuth na primeira versão.
Aceitação: sem armazenamento de IP bruto ou mensagem pessoal nos logs. Política de métricas/consentimento definida antes da instrumentação. Navegação e WhatsApp funcionam sem rastreamento ou cookies analíticos.

## Requisitos não funcionais
- Segredos no servidor/ambiente, nunca no navegador, Git ou logs. Acesso à API exclusivamente no worker; visitantes não disparam geração nem cobrança.
- Limites por item/lote/período, reserva transacional de orçamento, tokens de entrada/saída e timeout. Exibir consumo estimado separado do confirmado, moeda/modelo e tabela de custos versionada. Custos desconhecidos impedem geração comercial sem limite explícito.
- Tratar instruções dentro dos temas/arquivos como conteúdo não confiável. Validar estrutura, comprimento, fatos de alto risco, URLs, mídia e duplicidade; escapar saída. Nenhuma ferramenta autônoma de navegação ou execução para o modelo no MVP.
- Novas mutações com POST e CSRF específico, autorização no servidor e transações. Não ativar CSRF global inadvertidamente sobre webhooks existentes.
- Logs de ação, revisão e publicação sem prompts sensíveis. Remoção/retensão de jobs configurável; preservar trilha mínima de revisão conforme política documentada.
- Geração fora da requisição pública, consultas limitadas/paginadas, mídias derivadas sem recomprimir originais. Meta de laboratório proposta: LCP <=2,5s e CLS <=0,1 na página piloto com teste móvel comparável; resultados reais precisam ser medidos.
- Compatibilidade PHP 8.2 local e 8.4 do VPS, sem nova dependência externa obrigatória para exibir páginas.

## Fora do MVP
Publicação automática sem revisão, páginas para ofertas inexistentes, multiplicação mecânica por bairro, envio WhatsApp, checkout novo, campanhas pagas, imagens geradas por IA, migração de páginas atuais e garantia de posição no Google.

## Pendências de configuração antes do piloto
Confirmar famílias/modalidades, fatos comerciais, número WhatsApp, mídias autorizadas; escolher fornecedor/modelo de IA, teto financeiro e política de retenção. Validar agenda do worker no VPS e dados reais do estúdio. A definição destes itens não impede desenvolver com provedor falso.
