# Evidências locais — 23/10/2026

## Execução aprovada
Comando reproduzível: `php vendor/phpunit/phpunit/phpunit -c phpunit.local.xml --no-coverage --log-junit build/logs/seo-local.xml`.

Resultado: **17 testes, 52 assertions, sem falhas**. PHP 8.2.12; PHPUnit 10.5.63. Relatório de máquina em `build/logs/seo-local.xml` (artefato local).

Cobertura observada: banco SQLite em memória; filtro noindex preserva corpo/status; home, metadados e link comercial; investimento; privacidade; sitemap XML com investimento e apenas ensaio publicado; ensaio publicado, inexistente e despublicado; agendamento acessível com meta/header noindex; home sem header noindex; acesso anônimo bloqueado em administração/cliente; testes unitários preexistentes.

Foi encontrado e corrigido erro real: Sitemap usava propriedades de objeto em registros retornados como arrays pelo modelo Hero. Teste agora exercita conteúdo publicado para evitar regressão.

## Isolamento
Configuração testing força SQLite :memory: após carregar configuração, independentemente das credenciais herdadas. Fixtures de páginas verificam driver/nome do banco antes de criar tabelas. Nenhum servidor de banco foi iniciado e nenhum banco existente foi migrado. Não foram executados endpoints de cobrança, reserva, envio ou armazenamento. Ambiente público e arquivos .env não foram alterados.

A primeira mensagem de erro de bootstrap era enganosa: arquivo existe e pode ser lido, mas is_readable falha sob restrições do Windows. A execução autorizada fora dessa restrição funciona; nenhuma dependência precisou ser reinstalada.

## Avanço adicional — MariaDB descartável
Instância MariaDB 10.4.32 inicializada em build/validation-mysql, limitada a 127.0.0.1:33317, sem serviço Windows. Banco jws_validation_only e usuário limitado a esse banco. Nenhum banco anterior foi reutilizado.

Comando: `php vendor/phpunit/phpunit/phpunit -c phpunit.migrations-local.xml --no-coverage --log-junit build/logs/migrations-local.xml`.
Resultado: **3 testes, 10 assertions aprovadas**, incluindo aplicação das migrations, persistência dos modelos e usuário admin fictício que abriu o painel (com noindex) e criou categoria. A autenticação desse teste usa actingAs do Shield; não comprova login por senha/OAuth no navegador.
Total combinado: **20 testes, 62 assertions**. A suíte SQLite foi reexecutada e permaneceu aprovada. Inicialização SQL de dependências de autenticação foi necessária antes das migrations App. Refresh destrutivo entre namespaces foi removido dos testes de modelos, e slugs aleatórios permitem repetição sem conflito.

## Limites e bloqueios reais
- Suíte histórica que executa migrações de aplicação falha em SQLite: migration AddIntentionIdToBookings contém ALTER TABLE ADD CONSTRAINT específico de MySQL. Essa limitação não foi contornada alterando migrations de produção.
- MySQL local não estava disponível na porta 3306; foi usada instância descartável separada na porta 33317. A suíte SEO usa schema mínimo; o runner MariaDB validou a execução das migrations.
- Não houve validação visual de navegador, login por senha, CRUD completo, upload, galeria com arquivos reais, OAuth ou fluxo completo de compra/pagamento. Acesso administrativo e criação de categoria passaram via sessão simulada do Shield.
- Integrações precisam de adaptadores falsos/fixtures e testes de contrato locais antes de acionar seus endpoints. Sandbox remoto não foi usado porque esta execução foi mantida local.
- Produção, indexação no Google e métricas de desempenho continuam sem validação.

## Próxima etapa local
Expandir fixtures de catálogo, clientes e pedidos. Implementar substitutos locais de pagamento, agenda, e-mail e armazenamento. Executar demais cenários autenticados e percursos de navegador da matriz validation.md; só então autorizar tecnicamente a liberação. A presente suíte é uma base de regressão, não um certificado de validação completa do sistema.

## Reprodução da instância descartável
Inicializar uma única vez com mysql_install_db.exe --datadir apontando a build/validation-mysql e --port=33317. Iniciar mysqld.exe com --no-defaults, --basedir=C:/xampp/mysql, --datadir apontando a pasta descartável, --port=33317 e --bind-address=127.0.0.1. Executar `php tests/prepare-mysql-local.php` (confere o diretório real antes de preparar), seguido da suíte phpunit.migrations-local.xml. Encerrar apenas essa instância com mysqladmin.exe --host=127.0.0.1 --port=33317 --user=root shutdown. Não utilizar serviços Windows ou dados do MySQL habitual.
