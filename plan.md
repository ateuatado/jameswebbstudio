# Planejamento e Alinhamento: James Webb Studio

**Data**: 18 de Julho de 2026
**Objetivo**: Mapear detalhadamente o que já está implementado no sistema e o que são tarefas futuras (não implementadas).

---

## 🟢 1. O Que Já Está Implementado (Atual)

### 1.1 Vendas e Checkout
- **Catálogo Dinâmico:** Gerenciamento de Serviços e Pacotes pelo Painel de Administração (`ServiceController`, `PackageController`, `CategoryController`).
- **Pagamento com Mercado Pago:** O cliente faz o checkout da compra de pacotes pagando de forma integral. A integração já aceita PIX e Cartão de Crédito (com parcelamento) via SDK do Mercado Pago.
- **Cupons de Desconto:** Geração e resgate de cupons de até 100% de desconto, utilizados para trocar serviços por portfólio (`CouponController`).
- **Integração Viral:** Conexão para campanhas e boca-a-boca com o sistema `viral.2gotas.com.br`.

### 1.2 Agendamento de Ensaios
- **Consumo de API Externa:** O sistema de calendário no site lê as disponibilidades através da API externa hospedada em `agenda.marcosantofoto.com.br`.
- **Registro de Agendamento:** Clientes confirmados reservam seus horários consumindo esta API e bloqueando a agenda (`ScheduleController`, `BookingController`).

### 1.3 Sessão de Fotos, AWS e Portal do Cliente
- **Tethering:** As câmeras transmitem as fotos em tempo real para a AWS durante a sessão.
- **Projetos do Cliente:** O administrador cadastra um projeto no painel, que gera o arquivo de configuração para conectar o estúdio ao bucket AWS do cliente.
- **Portal do Cliente Ativo:** O cliente pode se autenticar (Shield), visualizar, avaliar e selecionar as fotos hospedadas na AWS.
- **Inteligência Artificial (AWS Rekognition):** As fotos passam pela IA da Amazon para extrair tokens visuais e fazer o reconhecimento facial dos clientes.

### 1.4 Geração de Documentos (PDF)
- **Contratos Dinâmicos:** O painel administra cláusulas/seções contratuais (`ContractSectionController`) gerando PDFs assináveis via DomPDF.
- **Guias de Ensaio:** Geração de PDFs explicativos com dicas de poses, roupas e maquiagem (`GuideSectionController`).

### 1.5 Administração e Configuração
- **Gestão Total:** Dashboard do Admin para gerenciar Heros (CtaBlocks e publicação da página inicial), Usuários, Projetos e Configurações Globais (StudioSettings).
- **Leads (Básico):** O `IntentionController` captura a intenção dos visitantes e os exibe no painel apenas como visualização de interessados/carrinhos abandonados.

---

## 🔴 2. O Que NÃO Está Implementado (Tarefas/Backlog)

As funcionalidades abaixo ainda precisam ser desenvolvidas ou aprimoradas em futuras iterações. Elas estão mapeadas no arquivo `tasks.md`.

### 2.1 Novas Funcionalidades de Negócio
- **Pagamento por Sinal (Reserva):** Modificar o fluxo de checkout para cobrar apenas um sinal percentual e registrar o restante como saldo devedor.
- **Reagendamento pelo Próprio Cliente:** Criar um módulo no Portal do Cliente integrado ao `AgendaProxy` para auto-reagendamento.
- **Régua de Automação para Leads:** Expandir o `IntentionController` com disparos automáticos (ex: carrinho abandonado via AWS SES) e funil de vendas ativo.

### 2.2 Débitos Técnicos e Arquitetura (A Otimizar)
- **Consultas N+1 (Admin):** O `ClientProjectController` atualmente varre o banco de forma ineficiente, podendo causar lentidão. Precisa ser refatorado para usar relacionamentos (`JOIN`).
- **Custo e Gargalo da AWS:** O sistema de Polling em tempo real faz requisições pesadas diretamente na S3. Deve ser migrado para leitura local com sincronização via AWS Webhooks/EventBridge.
- **Fat Controller de Checkout:** A classe `PackageCheckout` acumula muita lógica de negócios e contato direto com SDK. Deve ser isolada em "Services" para facilitar manutenções futuras.

### 2.3 Acervo Público e Edições Fotográficas
- **Fase editorial/admin concluída (29/09/2026):** migrações, modelos, CRUD protegido em `/admin/fotos`, upload separado entre versão web e original privado, galeria pública em `/fotos` e `/fotos/{slug}`, compartilhamento social, especificações das edições e remoção do catálogo legado de molduras.
- **Próxima fase de relacionamento (30/09/2026):** comentários autenticados por fotografia, retorno à imagem após login/cadastro e trilha individual de visualizações com repetições preservadas. Login social será conectado após configurar as credenciais OAuth/OIDC do provedor escolhido.
- **Propósito único:** o acervo de `heroes` permanece como portfólio de ensaios e páginas de copy. `Fotos` é o acervo artístico; toda oferta comercial nasce da fotografia e não de um catálogo independente.
- **Fotos:** `/fotos` exibe uma capa por galeria. Cada galeria abre uma sequência navegável de fotografias, com narrativa/histórico, contexto técnico, retorno ao acervo, moldura selecionável e bloco “Do pixel ao papel” quando a fotografia tiver edições à venda.
- **Administração:** `/admin/fotos` concentra publicação, upload seguro, ordenação, prévia e as opções comerciais de cada fotografia: tamanhos, preço, disponibilidade e tamanho personalizado.
- **Dados e integridade:** `photo_works`, `photo_work_images` e `photo_print_options`. Valores monetários serão armazenados em centavos; estoque e confirmação de pagamento serão transacionais quando o checkout for incluído.
- **Entrega definida:** o checkout oferecerá retirada no estúdio ou entrega por frete. Retirada dispensa cobrança de frete; entrega requer CEP/endereço e cotação antes do pagamento. A integração inicial será Frenet para **PAC e SEDEX** dos Correios, isolada em um serviço de frete para que possa migrar para contrato direto no futuro. A cotação será sempre revalidada antes do pagamento, sem vencimento exibido ao cliente. Ainda é necessário definir política de reserva do Pix, prazo de produção e se haverá edição limitada ou reprodução sob encomenda.
