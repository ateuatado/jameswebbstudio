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

## Sucesso e Requisitos
- **Tecnologias:** PHP 8.2 (CodeIgniter 4), AWS SDK, Mercado Pago DX-PHP, DomPDF, ip-api.com (geolocalização), Chart.js (dashboards).

- **Metas de Sistema:** Tolerância alta a upload massivo de imagens, processamento de filas (Rekognition) sem travar a interface do cliente, e transações de pagamento confiáveis.
