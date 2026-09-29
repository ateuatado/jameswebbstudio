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

### 9. Acervo Público e Edições Fotográficas (em implementação)

#### 9.1 Decisão de arquitetura
- **Não reutilizar `heroes`/`photos`:** o módulo atual representa ensaios e landing pages com CTA para contratação. Ele deve permanecer intacto para evitar que regras de venda, estoque e metadados de obra contaminem o fluxo de ensaios.
- **Fotos** é o único acervo de fotografia autoral: uma galeria editorial/artistica orientada à apreciação da obra, à sua história e à sua materialização em impressão.
- Não haverá catálogo, rota ou entidade comercial independente. Uma edição comercial pertence diretamente a uma fotografia da galeria; assim, a venda nasce da obra, alinhada ao posicionamento “do pixel ao papel”.
- O menu público deve manter **Ensaios** (fluxo atual) e **Fotos**; a compra é descoberta dentro da fotografia.

#### 9.2 Experiência pública — Fotos
- Rotas propostas: `GET /fotos` (grade de galerias) e `GET /fotos/{slug}` (exploração de uma galeria).
- Cada item da grade representa uma galeria, com uma única imagem de capa, título, ano/coleção e quantidade de fotografias. O clique abre a galeria, sem expor todas as imagens na página inicial.
- A página da galeria deve apresentar uma fotografia por vez, com controles laterais de anterior/próxima, retorno para a capa do acervo e navegação por teclado. Ela deve informar título, descrição curta, histórico/narrativa, data ou ano, local, técnica/processo, coleção/tema e créditos quando aplicáveis.
- A fotografia é apresentada com passe-partout e moldura. O visitante pode alternar localmente as variações cinza, preta, marrom e branca; a escolha não altera o cadastro da obra.
- A navegação deve permitir avançar/voltar entre obras, retornar à grade e compartilhar uma URL estável. Modal/lightbox pode complementar a página, mas não substituí-la, para preservar SEO e acessibilidade.
- Fotografias marcadas como vendáveis exibem o bloco **“Do pixel ao papel”**, com tamanhos padrão, preço de cada edição e o CTA para solicitar a edição. Fotos não vendáveis permanecem puramente editoriais.
- A configuração deve oferecer tamanhos proporcionais à imagem, com uma prévia de corte e confirmação se algum formato exigir recorte. O pedido de tamanho personalizado é opcional por fotografia e deve deixar explícito que a proporção original será preservada.
- O CTA comercial inicial registra o interesse na edição/tamanho selecionado. O checkout completo evoluirá no mesmo fluxo, sem criar uma área paralela de produtos.

#### 9.3 Compra e entrega de edições
- No checkout, o comprador escolherá obrigatoriamente entre **retirada no estúdio** e **entrega por frete**. Retirada não cobra frete e apresenta instruções/endereço após a confirmação; entrega exige CEP/endereço e só pode prosseguir após o valor/prazo do frete ser apresentado e aceito.
- A cotação de entrega será feita no servidor pela API da **Frenet**, inicialmente com serviços dos **Correios**. A chave da API nunca será exposta ao navegador. A camada de cotação deve ser encapsulada em um serviço próprio para permitir futura troca por contrato direto com os Correios ou outro agregador.
- A cotação usará CEP de origem cadastrado no Estúdio, CEP de destino, peso e dimensões embaladas da edição. O checkout exibirá inicialmente **PAC** e **SEDEX** dos Correios, desde que disponíveis para o CEP consultado; a escolha, valor, prazo, serviço e momento da cotação serão gravados no pedido. A cotação não terá prazo de expiração exibido, mas será obrigatoriamente recalculada imediatamente antes de criar o pagamento; se preço, prazo ou serviço mudar, o comprador verá e aceitará a nova cotação antes de prosseguir.
- O botão de compra só aparece para edição disponível. O pedido deve registrar um *snapshot* imutável da fotografia, tamanho, preço, especificações e imagem; alterações posteriores no acervo não podem modificar uma venda já iniciada.
- O estoque deve ser reduzido ou reservado em transação no início do pagamento e devolvido quando o pagamento expirar/cancelar. Webhooks do Mercado Pago são a fonte de verdade para confirmação.

#### 9.4 Administração
- Criar um módulo protegido por `group:admin,superadmin`: **Acervo de Fotos** (`/admin/fotos`), com CRUD, rascunho/publicado, ordenação, imagem de capa, múltiplas fotografias, slug único, prévia pública, exclusão com confirmação e validação de upload (MIME real, tamanho e dimensão).
- No cadastro da galeria: título, slug, descrição, história/histórico, data/ano, local, técnica/processo, coleção/tema, créditos, texto alternativo, imagem de capa e ordem de exibição.
- Para cada fotografia interna, o painel permite marcá-la para venda e cadastrar tamanhos padrão, dimensões, preço, disponibilidade, ordem e permissão/observação de tamanho personalizado. Estoque, produção, acabamento e frete serão acrescentados ao mesmo registro de edição quando o checkout for implementado.

#### 9.5 Modelo de dados proposto
- `photo_works`: metadados editoriais de uma galeria fotográfica; `slug` único, publicação e ordenação.
- `photo_work_images`: fotografia de capa e fotografias internas de uma galeria, com texto alternativo, ordem e sinalização comercial opcional.
- `photo_print_options`: tamanhos/edições vendáveis de uma fotografia, com dimensão, preço em centavos, disponibilidade e ordem.
- Futuras tabelas de pedido devem referenciar a fotografia e a edição selecionada, registrando comprador, modalidade (`pickup` ou `shipping`), endereço quando aplicável, frete, pagamento e o *snapshot* da edição no momento da compra.
- As imagens devem ficar em armazenamento público controlado e fora do Git; arquivos originais e derivados web devem ter nomes não previsíveis. A implementação deve gerar versões otimizadas (miniatura, grade e detalhe) sem expor o original de impressão.

#### 9.6 SEO, acessibilidade e operação
- Cada obra pública deve possuir `title`, meta description, Open Graph e `Product`/`VisualArtwork` estruturado quando aplicável. Obras esgotadas continuam indexáveis; rascunhos não.
- Toda imagem deve ter texto alternativo editável, *lazy loading*, dimensões reservadas para evitar salto de layout e navegação por teclado no lightbox.
- O sitemap deve incluir apenas galerias publicadas. A exclusão deve remover referências de mídia e nunca afetar `heroes`, `photos` de ensaios ou galerias privadas de clientes.

#### 9.7 Decisões de negócio pendentes
- **Decidido:** a compra oferecerá retirada no estúdio e entrega por frete, cotada inicialmente pela API da Frenet para os serviços **PAC** e **SEDEX** dos Correios. A cotação não terá vencimento exibido, mas será revalidada obrigatoriamente antes do pagamento. A implementação deve cadastrar CEP/endereço e instruções de retirada nas configurações do estúdio.
- Definir se haverá obras únicas, séries limitadas numeradas, reproduções sob encomenda ou combinação dos três modelos.
- Definir política de reserva durante Pix, prazo de produção, embalagem, devolução e atendimento pós-venda.
- Definir política de preço, estoque/produção, acabamento, embalagem e pedido mínimo para tamanhos personalizados.

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

### User Story 4 - Descobrir Fotografia Autoral (Priority: P2 — Planejada)
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
