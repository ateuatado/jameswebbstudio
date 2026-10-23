# Evidências da implementação local

Data: 23/10/2026. Estado: pronto para avaliação editorial local; não publicado no VPS.

## Resultados
- Suíte em memória: 37 testes, 113 assertions aprovadas. Inclui regressões SEO existentes, schema/derivados, páginas públicas, snapshots, avisos de fatos, conflitos, redirecionamentos, arquivo 410, autorização, prévia, CSRF, jobs, cancelamento, idempotência, orçamento, lease expirado e mocks da DeepSeek.
- Suíte MariaDB descartável: 4 testes, 17 assertions aprovadas, incluindo aplicação de migrations, persistência dos modelos, painel admin, categoria fictícia e novo módulo. Instância 127.0.0.1:33317, banco/usuário próprios.
- Integração real DeepSeek: autenticação HTTP 200 e uma geração validada com 303 tokens de entrada e 534 de saída. Sem publicação; não contabilizada como teste simulado nem como prova de veracidade editorial.
- Banco local jws: backup privado em build/local-backup antes da instalação; somente migration nova aplicada. Quatro famílias iniciais cadastradas idempotentemente. Sem contexto/WhatsApp preenchidos por suposição e sem páginas publicadas.
- Chave apenas no .env ignorado; nenhuma chave na documentação/código. Configuração de produção continua desabilitada por padrão.

## Limites
Sem revisão visual no navegador, sem execução em PHP 8.4 do VPS nesta entrega e sem piloto de três páginas com WhatsApp real confirmado. Não houve testes de integrações financeiras/agendamento nem alteração nesses módulos. A suíte legada não cobre todos os percursos comerciais; não garante ausência de regressões.
Reservas de consumo usam preços conservadores configurados e não substituem fatura/limites de conta. Retentativas de resultado incerto são manuais. Revisão factual e equivalência semântica de temas continuam humanas.

## Reprodução
Ver quickstart.md para operação, configuração, comandos, ativação controlada, worker, limites e pendências. Os testes podem ser executados com phpunit.local.xml e phpunit.migrations-local.xml. Backups, relatórios e smoke da API ficam fora do Git.
