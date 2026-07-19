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
