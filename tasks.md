# Tasks: Evolução do James Webb Studio

Este documento lista as tarefas pendentes de acordo com a regra de negócio estabelecida e requisitos futuros.

## 1. Checkout e Pagamentos
- `[ ]` **Pagamento de Sinal/Reserva:** Criar funcionalidade para permitir que o cliente pague apenas uma porcentagem (%) do pacote online (reserva da data), e o restante presencialmente.
- `[ ]` **Validação Mercado Pago:** Certificar que o parcelamento com juros/sem juros e conciliação de Pix estão rodando 100% de acordo com as chaves de produção.

## 2. Agendamento e Calendário
- `[ ]` **Reagendamento de Cliente:** Implementar rota e interface no Portal do Cliente (`Client/Portal`) permitindo que o próprio usuário reagende sua sessão, consumindo a API do `agenda.marcosantofoto.com.br`. Adicionar regras de antecedência mínima para cancelamento/reagendamento.

## 3. Gestão de Leads (Intention)
- `[ ]` **Régua de Recuperação:** Desenhar fluxo para capturar clientes no `IntentionController` e integrá-los a um disparo de e-mails automático (ex: AWS SES) ou pipeline visível no Painel Admin para o time de vendas.

## 4. Portal do Cliente
- `[ ]` **Seleção de Fotos Avançada:** Refinar a interface onde o cliente escolhe as fotos (garantir que as requisições para a AWS sejam rápidas ou feitas através de links pré-assinados com cache).

## 5. Cupons e Marketing Viral
- `[ ]` **Tracking do Viral:** Validar comunicação completa entre a plataforma do estúdio e o `viral.2gotas.com.br` para garantir que conversões e usos de cupons gerem os gatilhos corretamente.

## 6. Débitos Técnicos e Refatoração (Urgente)
- `[ ]` **Consultas N+1 no Admin:** Refatorar `ClientProjectController` para usar `JOINs` no banco de dados ao invés de buscar os nomes de usuários e pacotes dentro de laços de repetição `foreach`.
- `[ ]` **Polling da AWS S3:** Modificar a rota `pollInteractions` para não fazer chamadas síncronas para a AWS S3 (risco de custos altos e gargalo). Implementar leitura apenas do banco local e sincronizar via AWS EventBridge/Webhooks.
- `[ ]` **Refatoração do Checkout:** Mover a regra de negócios pesada do `PackageCheckout::buy()` (quase 200 linhas) para um ou mais Serviços (`PaymentService`, `OrderService`), diminuindo o acoplamento com o SDK do Mercado Pago.

## 7. Acervo Público e Edições Fotográficas
- `[ ]` **Checkout e modalidades de entrega:** Implementar retirada no estúdio e entrega por frete; pedidos devem registrar a modalidade escolhida e endereço apenas quando aplicável.
- `[ ]` **Integração de frete:** Usar a API Frenet, inicialmente com serviços dos Correios; encapsular o provedor em um serviço de frete e manter a chave somente no servidor.
- `[ ]` **Serviços dos Correios:** Oferecer PAC e SEDEX quando disponíveis para o CEP de destino.
- `[ ]` **Revalidação de frete:** Manter a cotação exibida sem vencimento, mas recalcular obrigatoriamente antes de gerar o pagamento e pedir nova aceitação se houver alteração.
- `[ ]` **Decisões comerciais restantes:** Definir prazo de produção, política de reserva para Pix, obras únicas versus edições/reproduções e política de devolução.
- `[x]` **Schema do Acervo de Fotos:** Criar migrações e modelos para `photo_works`, `photo_work_images` e `photo_print_options`, sem modificar `heroes` ou `photos` existentes; separar versão web e original privado.
- `[x]` **Admin de Fotos:** Criar CRUD protegido em `/admin/fotos` com rascunho/publicação, título, narrativa/histórico, técnica, data/ano, local, coleção, créditos, texto alternativo, capa, ordenação, upload e CRUD de edições.
- `[x]` **Galeria pública de Fotos:** Criar `/fotos` e `/fotos/{slug}` com uma capa por galeria, filtros, navegação anterior/próxima, moldura selecionável, metadados SEO e inclusão seletiva no sitemap.
- `[x]` **Edições à venda:** Associar tamanhos, preço, disponibilidade e pedido de tamanho personalizado diretamente a cada fotografia, com CRUD completo das edições e sem catálogo comercial independente.
- `[ ]` **Checkout de edições:** Implementar fluxo de pedido/snapshot da fotografia e do tamanho selecionado, reserva transacional quando aplicável, Mercado Pago, webhook idempotente, expiração/cancelamento e recuperação de estoque.
- `[ ]` **Mídia, segurança e qualidade:** Completar derivados otimizados, testes de estoque concorrente, checkout/pagamento e auditoria final de acessibilidade/SEO. A autorização admin, a validação de upload, a prévia social e a separação do original privado já foram implementadas.
- `[x]` **Comentários nas fotografias:** Criar comentários vinculados ao usuário, posicionados antes do rodapé, com retorno à fotografia após autenticação.
- `[x]` **Histórico de navegação:** Registrar cada visualização por fotografia, usuário/token anônimo, data/hora e sequência, preservando repetições.
- `[x]` **Login social:** Integrar Google OAuth/OIDC ao Shield, com associação segura de identidades, configuração por ambiente e retorno para a fotografia de origem. Falta apenas validar as credenciais e a URL de callback no VPS.
- `[ ]` **Moderação e inteligência comercial:** Criar painel para moderar comentários, ferramentas antispam, consentimento de analytics e relatórios de recorrência por fotografia.
