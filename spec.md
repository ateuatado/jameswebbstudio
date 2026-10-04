# Spec: Sistema James Webb Studio

**Criado**: 18 de Julho de 2026
**Status**: Documentação de Arquitetura Existente

## Visão Geral
Este documento descreve a arquitetura, regras de negócios e funcionalidades da plataforma do **James Webb Studio**. O sistema atende todo o ciclo de vida do cliente fotográfico: landing pages (Hero/CTA), venda de pacotes e serviços (Mercado Pago), agendamento integrado, portal do cliente para seleção/visualização de fotos via AWS, até o painel administrativo completo.

## Módulos e Regras de Negócio

### 1. Checkout e Vendas (`PackageCheckout` / `OrderController`)
- **Venda de Pacotes e Serviços:** O estúdio oferece Serviços (Services) que podem compor Pacotes (Packages). 
- **Pagamento Integrado:** O fluxo de compra ocorre via Mercado Pago, aceitando pagamentos por Pix e Cartão de Crédito (com suporte a parcelamento).
- **Regra Atual:** O cliente paga o valor integral no ato da compra online.
- **Integrações Futuras:** Possibilidade de cobrança apenas de um "sinal" (reserva) com o restante pago presencialmente ou em outra etapa.

### 2. Agendamento (`ScheduleController` / `BookingController`)
- **API Externa:** O sistema de calendário não gerencia janelas internamente; ele se comunica com a API proprietária em `agenda.marcosantofoto.com.br`, de onde extrai a disponibilidade do estúdio.
- **Regras:** Clientes com pacotes ou cupons pagos utilizam o site para agendar o horário no estúdio.
- **Integrações Futuras:** Implementar a funcionalidade para que o próprio cliente possa reagendar sua sessão diretamente pelo Portal.

### 3. AWS, Fotos e Portal do Cliente (`ClientProjectController` / `PhotoSearchController`)
- **Upload via Tethering:** Durante a sessão de fotos, as imagens são transmitidas (tethering) diretamente para a AWS.
- **Projetos do Cliente:** O Admin cadastra o "Projeto do Cliente" (Client Project) no painel. Esse cadastro gera um arquivo/credencial para o software local conectar-se ao AWS e fazer o upload no bucket correto.
- **Interação do Cliente:** Pelo Portal, o cliente pode visualizar, avaliar e selecionar as fotos para o seu álbum/portfólio final diretamente da AWS.
- **Inteligência Artificial (AWS Rekognition):** O sistema utiliza IA para avaliar, criar tokens e identificar os rostos dos clientes nas fotos (Facial Recognition / Photo Search).

### 4. Campanhas e Cupons (`CouponController`)
- **Finalidade:** Utilizados para oferecer descontos de até 100% para convidados, muitas vezes em troca de participação em portfólio.
- **Marketing Viral:** O sistema se conecta/interage com `viral.2gotas.com.br` para campanhas de viralização, incentivando indicações.

### 5. Documentos e PDFs (`ContractSectionController` / `GuideSectionController`)
- **DomPDF:** Utilizado amplamente para a geração dinâmica de documentos vitais.
- **Contratos:** Geração do contrato de prestação de serviço baseado nas seções configuradas no admin.
- **Artefatos Explicativos:** Geração de Guias de Ensaio (orientações de vestuário, maquiagem, poses, etc.) entregues aos clientes.

### 6. Gestão de Leads (`IntentionController`)
- **Status Atual:** Módulo básico que capta e permite a visualização de leads (intenções de compra ou carrinhos abandonados).
- **Integrações Futuras:** Necessita de uma estruturação melhor (ex: réguas de relacionamento, disparo de e-mails via AWS SES, funil de vendas).

### 7. Rastreamento de Visitas (`TrackingController` / `Admin\TrackingLinkController`)
- **Objetivo:** Rastrear a origem dos visitantes provenientes de redes sociais e campanhas de marketing, combinando URLs curtas legíveis com parâmetros UTM internos.
- **Links Rastreados:** O Admin cria links com slug personalizado (ex: `ig-bio`), definindo source, medium e campanha. O sistema gera a URL curta `jameswebbstudio.com.br/r/{slug}`.
- **Captura de Dados:** A cada visita, o sistema registra: data/hora, IP anonimizado, geolocalização (país, região, cidade via `ip-api.com`), tipo de dispositivo (mobile/desktop/tablet), OS e browser.
- **Dashboard Admin:** Painel com filtros por período e source, exibindo: total de visitas, gráfico de visitas por dia, ranking por utm_source, ranking por campanha, top 10 cidades e divisão por dispositivo.
- **Segurança/LGPD:** IPs são anonimizados (último octeto removido) antes de persistir.

### 8. Painel Administrativo (`Admin/*`)
Um painel completo gerenciando:
- **Catálogo:** Categorias, Serviços e Pacotes.
- **CRM / Vendas:** Projetos dos Clientes, Pedidos (Orders), Leads (Intention) e Reservas (Bookings).
- **Conteúdo / Landing Pages:** Gerenciamento das seções Hero (HeroController / CtaBlock) da página inicial.
- **Configurações:** Studio Settings, Seções de Contrato e Seções de Guia.
- **Usuários:** Gerenciamento de níveis de acesso e equipe (UserManagement).

### 9. Acervo Público e Edições Fotográficas (fase 1 implementada)

**Estado em 29/09/2026:** a fase editorial e administrativa está implementada. O
checkout, a reserva de estoque, o pagamento e a cotação de frete ainda são etapas
futuras.

#### 9.1 Decisão de arquitetura
- **Não reutilizar `heroes`/`photos`:** o módulo atual representa ensaios e landing pages com CTA para contratação. Ele deve permanecer intacto para evitar que regras de venda, estoque e metadados de obra contaminem o fluxo de ensaios.
- **Fotos** é o único acervo de fotografia autoral: uma galeria editorial/artistica orientada à apreciação da obra, à sua história e à sua materialização em impressão.
- Não haverá catálogo, rota ou entidade comercial independente. Uma edição comercial pertence diretamente a uma fotografia da galeria; assim, a venda nasce da obra, alinhada ao posicionamento “do pixel ao papel”.
- O menu público deve manter **Ensaios** (fluxo atual) e **Fotos**; a compra é descoberta dentro da fotografia.

#### 9.2 Experiência pública — Fotos
- Rotas implementadas: `GET /fotos` (grade de galerias) e `GET /fotos/{slug}` (exploração de uma galeria). A fotografia pode ser compartilhada por URL estável com o parâmetro de imagem.
- Cada item da grade representa uma galeria, com uma única imagem de capa, título, ano/coleção e quantidade de fotografias. O clique abre a galeria, sem expor todas as imagens na página inicial.
- A página da galeria deve apresentar uma fotografia por vez, com controles laterais de anterior/próxima, retorno para a capa do acervo e navegação por teclado. Ela deve informar título, descrição curta, histórico/narrativa, data ou ano, local, técnica/processo, coleção/tema e créditos quando aplicáveis.
- A fotografia é apresentada com passe-partout e moldura. O visitante pode alternar localmente as variações cinza, preta, marrom e branca; a escolha não altera o cadastro da obra.
- A navegação deve permitir avançar/voltar entre obras, retornar à grade e compartilhar uma URL estável. Modal/lightbox pode complementar a página, mas não substituí-la, para preservar SEO e acessibilidade.
- Fotografias marcadas como vendáveis exibem o bloco **“Do pixel ao papel”**, com tamanhos padrão, preço de cada edição e o CTA para solicitar a edição. Fotos não vendáveis permanecem puramente editoriais.
- A configuração deve oferecer tamanhos proporcionais à imagem, com uma prévia de corte e confirmação se algum formato exigir recorte. O pedido de tamanho personalizado é opcional por fotografia e deve deixar explícito que a proporção original será preservada.
- O CTA comercial inicial apresenta a seleção e registra a intenção no contexto da fotografia; o checkout completo evoluirá no mesmo fluxo, sem criar uma área paralela de produtos.

#### 9.3 Compra e entrega de edições
- No checkout, o comprador escolherá obrigatoriamente entre **retirada no estúdio** e **entrega por frete**. Retirada não cobra frete e apresenta instruções/endereço após a confirmação; entrega exige CEP/endereço e só pode prosseguir após o valor/prazo do frete ser apresentado e aceito.
- A cotação de entrega será feita no servidor pela API da **Frenet**, inicialmente com serviços dos **Correios**. A chave da API nunca será exposta ao navegador. A camada de cotação deve ser encapsulada em um serviço próprio para permitir futura troca por contrato direto com os Correios ou outro agregador.
- A cotação usará CEP de origem cadastrado no Estúdio, CEP de destino, peso e dimensões embaladas da edição. O checkout exibirá inicialmente **PAC** e **SEDEX** dos Correios, desde que disponíveis para o CEP consultado; a escolha, valor, prazo, serviço e momento da cotação serão gravados no pedido. A cotação não terá prazo de expiração exibido, mas será obrigatoriamente recalculada imediatamente antes de criar o pagamento; se preço, prazo ou serviço mudar, o comprador verá e aceitará a nova cotação antes de prosseguir.
- O botão de compra só aparece para edição disponível. O pedido deve registrar um *snapshot* imutável da fotografia, tamanho, preço, especificações e imagem; alterações posteriores no acervo não podem modificar uma venda já iniciada.
- O estoque deve ser reduzido ou reservado em transação no início do pagamento e devolvido quando o pagamento expirar/cancelar. Webhooks do Mercado Pago são a fonte de verdade para confirmação.

#### 9.4 Administração
- Módulo implementado e protegido por `group:admin,superadmin`: **Acervo de Fotos** (`/admin/fotos`), com CRUD, rascunho/publicado, ordenação, imagem de capa, múltiplas fotografias, slug único, prévia pública, exclusão com confirmação e validação de upload (MIME real, tamanho e dimensão).
- No cadastro da galeria: título, slug, descrição, história/histórico, data/ano, local, técnica/processo, coleção/tema, créditos, texto alternativo, imagem de capa e ordem de exibição.
- Para cada fotografia interna, o painel permite marcá-la para venda e cadastrar tamanhos padrão, dimensões, preço, disponibilidade, ordem e permissão/observação de tamanho personalizado. Estoque, produção, acabamento e frete serão acrescentados ao mesmo registro de edição quando o checkout for implementado.

#### 9.5 Modelo de dados proposto
- `photo_works`: metadados editoriais de uma galeria fotográfica; `slug` único, publicação e ordenação.
- `photo_work_images`: fotografia de capa e fotografias internas de uma galeria, com texto alternativo, ordem, sinalização comercial opcional, versão web e caminho do original privado.
- `photo_print_options`: tamanhos/edições vendáveis de uma fotografia, com dimensão, preço em centavos, disponibilidade, ordem e especificações de impressão, moldura, fundo, proteção, peso e prazo de produção.
- O catálogo legado independente de molduras (`frames`/`frame_images`) foi removido; a edição pertence diretamente à fotografia.
- Futuras tabelas de pedido devem referenciar a fotografia e a edição selecionada, registrando comprador, modalidade (`pickup` ou `shipping`), endereço quando aplicável, frete, pagamento e o *snapshot* da edição no momento da compra.
- A publicação usa duas cópias enviadas manualmente: uma versão web pública, preparada antes do upload para galeria e compartilhamento, e um original privado para impressão em `writable/`. O sistema não redimensiona, rotaciona ou converte imagens automaticamente.

#### 9.6 SEO, acessibilidade e operação
- Cada fotografia pública possui título e descrição próprios, usados em `og:title`, `og:description` e Twitter; o título termina com “James Webb Studio”. A `og:image` é uma derivação persistente de 1200×630 da própria fotografia, com fundo neutro e sem corte quando a proporção exigir. O link social mantém `imagem` e `compartilhar` para seleção da obra e invalidação de cache; `Product`/`VisualArtwork` estruturado permanece previsto quando aplicável. Obras esgotadas continuam indexáveis; rascunhos não.
- Toda imagem deve ter texto alternativo editável, *lazy loading*, dimensões reservadas para evitar salto de layout e navegação por teclado no lightbox.
- O sitemap deve incluir apenas galerias publicadas. A exclusão deve remover referências de mídia e nunca afetar `heroes`, `photos` de ensaios ou galerias privadas de clientes.
- O site publica as rotas `/politica-de-privacidade` e `/termos-de-servico`, usadas também no cadastro do login Google e vinculadas no rodapé.

#### 9.7 Decisões de negócio pendentes
- **Decidido:** a compra oferecerá retirada no estúdio e entrega por frete, cotada inicialmente pela API da Frenet para os serviços **PAC** e **SEDEX** dos Correios. A cotação não terá vencimento exibido, mas será revalidada obrigatoriamente antes do pagamento. A implementação deve cadastrar CEP/endereço e instruções de retirada nas configurações do estúdio.
- Definir se haverá obras únicas, séries limitadas numeradas, reproduções sob encomenda ou combinação dos três modelos.
- Definir política de reserva durante Pix, prazo de produção, embalagem, devolução e atendimento pós-venda.
- Definir política de preço, estoque/produção, acabamento, embalagem e pedido mínimo para tamanhos personalizados.

#### 9.8 Comentários e inteligência de navegação
- Cada fotografia possui, ao final da página e antes do rodapé, uma área de comentários vinculada ao usuário autenticado.
- Ao tentar comentar sem sessão, o visitante é encaminhado ao login/cadastro e retorna à mesma galeria e fotografia após autenticar.
- O histórico registra cada visualização individual, sem deduplicar repetições. Usuários autenticados são vinculados por `user_id`; visitantes anônimos recebem um token persistente no navegador até que façam login.
- As visualizações devem preservar fotografia, data/hora e sequência, permitindo medir quais obras geram atenção recorrente e apoiar a venda sem confundir visualizações com intenção de compra.
- A primeira versão publica comentários diretamente; a moderação administrativa, denúncia, antispam e consentimento de analytics ficam planejados antes de uma operação em escala.
- O login social Google foi integrado ao CodeIgniter Shield por OAuth/OIDC, associando a identidade externa à conta existente sem duplicar usuários. As credenciais e a URL de retorno são configuradas por ambiente.

## 10. Prospecção contextual no Threads (especificação inicial)

### 10.1 Objetivo e posicionamento
- Criar um módulo interno para organizar abordagens humanas a partir de publicações públicas no Threads e conduzir oportunidades qualificadas até conversa, proposta e agendamento.
- O módulo registra apenas o contexto declarado na publicação (por exemplo: autocuidado, novo visual, aniversário, profissão, recomeço, intenção explícita de fazer ensaio ou afinidade estética), sem diagnosticar o estado emocional da pessoa.
- A experiência deve ser pessoal e cuidadosa: o sistema prepara comentário, mensagem privada e página personalizada a partir de um único cadastro, mas a publicação e o envio permanecem sob revisão humana.
- O objetivo é medir oportunidades qualificadas, conversas, agendamentos e faturamento; quantidade de comentários isoladamente não é métrica de sucesso.

### 10.2 Fluxo operacional
1. O operador identifica uma publicação pública e cadastra manualmente usuário, URL, texto e contexto declarado.
2. O sistema sugere categoria, prioridade, tom, ensaio/proposta e chamada para ação.
3. O sistema gera rascunhos de comentário público, mensagem privada e página personalizada.
4. O operador revisa e publica o comentário no Threads.
5. O operador envia o link da página pelo direct; o comentário pode apenas avisar que há uma mensagem privada.
6. O operador atualiza status e próxima ação no painel.
7. A oportunidade evolui para conversa, proposta, agendamento ou encerramento.

### 10.3 Privacidade e links
- O comentário público não deve conter o link da página personalizada nem expor o texto privado da abordagem.
- A página deve usar token aleatório, não exigir nome de usuário na URL, possuir `noindex` e não aparecer no sitemap.
- Token não é autenticação forte: a pessoa pode compartilhar o link. A primeira versão não deve exibir dados sensíveis nem interpretações psicológicas.
- O sistema deve permitir invalidar/regenerar o link e registrar somente métricas necessárias, em conformidade com a Política de Privacidade e a LGPD.
- Quando não for necessário personalizar, deve existir uma página pública genérica de campanha.

### 10.4 Dados da oportunidade
Uma oportunidade deve registrar:
- usuário/perfil do Threads, URL da publicação, texto copiado e data aproximada da coleta;
- categoria/contexto declarado, prioridade, cidade/região quando explicitamente disponível e responsável;
- comentário, direct e copy da página em versões editáveis;
- slug interno, token e URL da página personalizada;
- status, última ação, próxima ação e observações;
- resposta, conversa, proposta, agendamento e valor quando houver;
- encerramento e motivo, inclusive pedido de não contato.

Não devem ser armazenados rótulos como “carente”, “insegura” ou qualquer inferência clínica/psicológica.

### 10.5 Status do funil
- `identified`: oportunidade identificada;
- `drafting`: materiais em preparação;
- `ready`: materiais prontos para revisão;
- `commented`: comentário público publicado;
- `direct_sent`: link enviado no direct;
- `replied`: pessoa respondeu;
- `conversation`: conversa comercial iniciada;
- `proposal_sent`: proposta/condição enviada;
- `scheduled`: ensaio agendado;
- `won`: oportunidade convertida;
- `not_interested`: não houve interesse;
- `do_not_contact`: não abordar novamente;
- `expired`: oportunidade encerrada por prazo.

O painel deve prevenir duplicidade por usuário e mostrar quais membros da equipe já atuaram sobre a oportunidade.

### 10.6 Página personalizada
- A abertura deve reconhecer o tema da publicação sem sugerir que o estúdio conhece a intimidade da pessoa.
- Estrutura recomendada: reconhecimento do tema, transição para a proposta, portfólio/depoimento, experiência do ensaio, pacote/condição e CTA para conversar.
- A página pode reutilizar componentes de landing pages existentes, mas deve permitir substituir título, abertura, imagens, depoimento, oferta e CTA por oportunidade.
- A página deve identificar o Estúdio James Webb, localização, natureza comercial do convite e formas de contato.
- O conteúdo deve ser revisado antes da publicação e nunca afirmar que a pessoa é insegura, carente ou precisa de validação.

### 10.7 Operação compartilhada
- O painel deve exibir filas por responsável (você ou sua esposa), com a próxima ação mais urgente.
- Cada oportunidade deve possuir histórico de alterações e ações essenciais para evitar abordagem duplicada ou conflito entre operadores.
- Ações rápidas: copiar comentário, copiar direct, abrir página, abrir publicação original e avançar status.
- A automação não deve publicar comentários, enviar directs, seguir perfis ou reagir a publicações sem decisão humana explícita.

### 10.8 Indicadores
O dashboard deve filtrar por período, responsável, categoria e origem, exibindo oportunidades, abordagens, respostas, directs, páginas abertas, conversas, propostas, agendamentos, receita atribuída, tempo entre etapas e pedidos de não contato.

As métricas de página são sinais operacionais, não prova de intenção. O sistema não deve criar perfis comportamentais invasivos.

### 10.9 Segurança e qualidade
- Não abordar perfis possivelmente menores de idade, contas suspeitas ou pessoas que pediram para não receber contato.
- Não usar imagens da publicação em página personalizada sem base adequada; preferir contexto textual e portfólio próprio autorizado.
- Não revelar publicamente o nome, texto ou oferta personalizada de outra pessoa.
- Condições comerciais e descontos devem ser transparentes quando apresentados no direct ou na página.
- O módulo deve respeitar os termos aplicáveis da plataforma e operar como apoio à abordagem manual, não como ferramenta de spam.

## User Scenarios Principais

### User Story 1 - Compra e Agendamento (Priority: P1)
**Given** que um usuário está navegando nos pacotes do estúdio
**When** ele compra um pacote com Pix/Cartão via Mercado Pago
**Then** um Pedido é gerado, o pagamento é confirmado e ele ganha acesso a agendar via API (agenda.marcosantofoto.com.br).

### User Story 2 - Sessão de Fotos e IA (Priority: P1)
**Given** que o administrador criou o "Projeto do Cliente"
**When** a sessão fotográfica acontece, as fotos sobem via tethering para AWS
**Then** a AWS processa o Rekognition para tokens/faces e as imagens ficam imediatamente disponíveis no Portal do Cliente.

### User Story 3 - Seleção de Fotos no Portal (Priority: P1)
**Given** que as fotos do projeto estão na AWS
**When** o cliente acessa seu portal
**Then** ele consegue visualizar, curtir e selecionar as imagens desejadas para compor seu produto final.

### User Story 4 - Descobrir Fotografia Autoral (Priority: P2 — Implementada)
**Given** que uma fotografia está publicada no Acervo de Fotos
**When** uma pessoa visita `/fotos` e abre a obra
**Then** ela encontra a fotografia e seu contexto artístico em uma página pública, acessível e compartilhável.

### User Story 5 - Comprar um Quadro Disponível (Priority: P1 — Planejada)
**Given** que um quadro publicado possui estoque disponível
**When** uma pessoa inicia a compra e o pagamento é confirmado
**Then** o sistema registra um pedido com o snapshot da peça e atualiza sua disponibilidade sem permitir venda acima do estoque.

## Sucesso e Requisitos
- **Tecnologias:** PHP 8.2 (CodeIgniter 4), AWS SDK, Mercado Pago DX-PHP, DomPDF, ip-api.com (geolocalização), Chart.js (dashboards).

- **Metas de Sistema:** Tolerância alta a upload massivo de imagens, processamento de filas (Rekognition) sem travar a interface do cliente, e transações de pagamento confiáveis.
