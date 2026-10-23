# Operação do módulo — versão inicial implementada

## Estado em 23/10/2026
Implementado no monólito existente: páginas e famílias editoriais, contexto versionado, editor, prévia, revisão e publicação individual/coletiva, arquivamento, renomeação com 301, restauração de rascunhos, sitemap, CTA WhatsApp, DeepSeek JSON, fila persistente, worker CLI, cancelamento, repetição manual e limites conservadores de tokens/USD. Nenhuma alteração no significado de services, heroes, obras ou checkout.

Configuração pública e geração vêm DESATIVADAS por padrão no código. O ambiente local foi habilitado; produção exige configuração própria e novo segredo. A chave fornecida foi salva apenas no .env ignorado, sem reprodução neste documento. Foi feito teste real de autenticação (HTTP 200) e uma geração pequena (303 tokens de entrada, 534 de saída) validada como JSON; conteúdo ficou no build ignorado, sem publicação.

## Primeiro uso local
1. Entrar em /admin/paginas-servicos. O menu "Páginas de serviços" é distinto dos serviços comerciais de pacotes.
2. Abrir "Contexto aprovado e WhatsApp". Informar somente fatos reais e número com DDI, conferir e salvar. Não há número de contato fictício cadastrado pelo agente.
3. Quatro famílias iniciais já foram criadas localmente: ensaios, produtos, imóveis e moda. Editoriais ainda não publicados não aparecem no menu público ou sitemap.
4. Escolher família e inserir um ou mais temas, um por linha (até 25). Revisar lista, duplicatas exatas e endereços; confirmar criação. Marcar a opção IA se desejar enfileirar rascunhos.
5. Processar localmente com `php spark service-pages:work 1` (um item) ou `php spark service-pages:work 25` (até 25). O painel não chama a API na requisição HTTP; sem worker os itens aguardam na fila.
6. Reabrir o editor e revisar textos, avisos e direitos das fotografias. Salvar, abrir prévia e confirmar publicação. Avisos não resolvidos impedem publicação. Restaurar uma revisão não muda a versão pública até nova publicação.

## Configuração de ambiente (sem segredo de exemplo real)
```dotenv
servicepages.enabled = false
servicepages.generationEnabled = false
servicepages.apiKey = ''
servicepages.model = 'deepseek-flash'
servicepages.pricingModel = 'deepseek-flash'
servicepages.inputUsdPerMillion = 0.30
servicepages.outputUsdPerMillion = 1.20
servicepages.dailyUsdLimit = 0.25
servicepages.dailyTokenLimit = 100000
```
Tarifas conservadoras peak/cache-miss consultadas em 23/10/2026, em USD. Rever preços antes de ativar e ao trocar modelo; tabela de referência: https://api-docs.deepseek.com/quick_start/pricing/. Limites da aplicação não são limites de conta do fornecedor e não controlam usos externos da mesma chave.
Cada tentativa reserva o pior caso configurado. Reserva não é liberada em falha/timeout; o consumo pode ter ocorrido. O consumo reportado e custo estimado não substituem a fatura. Repetição exige confirmação e no máximo duas tentativas. Nenhuma repetição automática de chamada de resultado incerto.

## Instalação e atualização do VPS — ainda não executadas
Backup de banco e código, segredos fora do Git. `php spark service-pages:install` aplica somente a migration aditiva deste módulo e registra histórico; evita executar migrações históricas sobre um banco legado. Se já instalada, não faz down. `php spark db:seed ServiceContentFamilySeeder` é idempotente e só cadastra famílias. Novas alterações futuras de schema exigem nova migration, não editar a já aplicada.
Configurar production no servidor e chave nova do DeepSeek, WhatsApp e fatos no painel. Worker como usuário do site, via cron/supervisor. Sugestão cron uma vez por minuto: executar `php spark service-pages:work 1` a partir da raiz do projeto, com saída operacional para log privado; não incluir segredo na linha de comando. Claims de jobs e reservas de consumo são transacionais, impedindo dois workers de processar o mesmo item.
Ativar geração somente com limites confirmados. Ativar enabled após revisar o piloto. Desligar generationEnabled para parar novas chamadas mantendo páginas públicas; cancelar lotes para impedir que sejam retomados. Desligar enabled oculta todas as páginas do módulo e remove suas entradas do sitemap, sem alterar outras páginas. Não fazer rollback destrutivo de tabelas para reverter interface.

## Verificação
`php vendor/phpunit/phpunit/phpunit -c phpunit.local.xml --no-coverage`: suíte com banco em memória e provedores falsos. `phpunit.migrations-local.xml`: instância MariaDB descartável 127.0.0.1:33317 com banco/usuário restritos; instruções em Spec 001. Não usar configurações de produção em testes. Arquivos de relatório, backups e smoke da API permanecem em build ignorado.
HTTP público: página publicada 200, retirada após publicação 410, nunca publicada 404, endereço antigo 301. Prévia requer permissão e responde noindex/private,no-store. Verificar WhatsApp sem enviar mensagem, navegação e sitemap. Testar visualmente no celular/desktop antes de publicar no VPS.

## Limites desta versão
Revisão factual/semântica é humana: validação de JSON e bloqueio de HTML não provam veracidade. Duplicatas são detectadas pelo slug normalizado, não por equivalência semântica. CSV, agrupamento semântico assistido, edição coletiva de conteúdo, métricas/consentimento e retenção automática de jobs ficam para expansão. Fotos vêm de ensaios publicados; associação com acervo autoral exige regra própria de autorização. Não foram criadas três páginas comerciais sem dados/WhatsApp confirmados. Nenhum número de busca/ranking é garantido.

## Referências da integração
- https://api-docs.deepseek.com/api/create-chat-completion/
- https://api-docs.deepseek.com/guides/json_mode/
