# Plano técnico — módulo editorial de serviços

Estado: proposto, sem execução de migrations ou alterações de produto nesta entrega documental.

## Arquitetura
Novo módulo no monólito CodeIgniter. Controllers finos, Models/validação e serviços de domínio para geração, revisão, publicação e links de contato. Views públicas reutilizam layout; painel reutiliza admin/layout. Não acoplar conteúdo editorial ao significado financeiro de services nem migrar heroes para novo modelo.

## Etapas e gates
### F0 — Preparação e contratos
Inventariar colisões de rotas /servicos, permissões atuais, campos de StudioSettings e propriedade de mídia. Aprovar contratos e catálogo inicial; confirmar regras factuais. Provedor falso antes de API real. Atualizar spec quando decisões propostas mudarem.
Gate: três briefings de serviços reais e matriz de testes definida.

### F1 — Fundação editorial sem IA
Migrations aditivas para famílias, páginas, contexto, revisões e redirecionamentos. CRUD de rascunhos e editor de blocos conhecidos. Permissões granulares para leitura, edição, geração e publicação, mantendo o grupo de entrada admin/superadmin existente. Regras explícitas no AuthGroups; usuário comum e developer não ganham publicação por acidente.
Gate: cadastro/edição concorrente e permissões passando em banco descartável MariaDB, sem alterar catálogo de pacotes.

### F2 — Publicação e site
Rotas públicas e prévia autenticada; renderização server-side, índice de serviços e links de modalidades. Publicação transacional guarda revisão pública e lastmod somente por mudança pública. Integrar sitemap após passar testes de estado. WhatsApp validado e configurável. Imagens por IDs autorizados e derivados responsivos existentes, sem hotlink ou originais privados.
Gate: publicar/despublicar/restaurar, 301/404/410, noindex de prévia, canonical, sitemap, CTA e móvel/desktop verificados.

### F3 — API de IA individual
Interface de provedor com implementação falsa e adaptador HTTP real. Endpoint fixo por configuração controlada, allowlist de fornecedor, sem URL arbitrária do painel. Enviar contexto mínimo versionado; exigir resposta estruturada e validar também localmente. Fixar versão do prompt/esquema; testar modelo escolhido antes de uso. Nenhum fornecedor/modelo foi escolhido nesta spec.
Gate: rejeição de fatos inventados, HTML/instruções maliciosas, resposta inválida, erro/autenticação/quota sem perda de edição; segredo não aparece em logs.

### F4 — Fila e lotes
Jobs persistidos no banco atual e worker `php spark service-pages:work`, sem Redis obrigatório. Claims atômicos com lease/heartbeat e recuperação de travamento; concorrência inicial 1. Registro de tentativas e reserva de orçamento em transação. Exponential backoff limitado com jitter para falhas transitórias; erros permanentes não repetidos automaticamente. Timeout e limite global do worker. Publicação é etapa distinta de geração.
Gate: lote de 25 entradas com duplicatas, falhas parciais, cancelamento, expiração de lease e execução repetida não duplica nem estoura o teto local; faturamento externo em timeout permanece incerto e é reportado.

### F5 — Piloto e publicação assistida
Criar três páginas de famílias diferentes com revisão humana, primeiro local/homologação. Depois API real com teto baixo autorizado/configurado. Antes do VPS: backup de arquivos e banco, simulação das migrations, merge fast-forward com usuário operador e feature flag do módulo desligada até finalizar configuração. Não pedir/receber senhas no chat.
No VPS: migrations aditivas, worker configurado pelo operador, confirmar production, configurações WhatsApp/API e mídia, smoke tests antes de ativar índice/sitemap. Google recebe apenas as três páginas aprovadas. Outros módulos permanecem disponíveis.
Gate: regressão pública/admin/checkout/galeria, PageSpeed e Search Console registrados. Desativar geração mantém páginas publicadas acessíveis. Falha grave: ocultar novas páginas e parar worker, reverter código de forma compatível sem rollback destrutivo do banco.

### F6 — Expansão medida
Liberar lotes após avaliar qualidade, custos e sobreposição. CSV com limites de tamanho/linhas e validação de UTF-8; escape contra fórmulas ao exportar. Edição coletiva propõe novas revisões, não substitui publicação sem confirmação. Medir consultas relevantes, contatos e orçamento solicitado, sem atribuir venda ao simples clique.

## Migração e reversão
Índices únicos para slug/hierarquia e chave de idempotência; FKs para usuário/revisão conforme projeto. Sem drop/rename de tabelas legadas. Backup pré-migração e restore ensaiado em ambiente descartável. Não apagar dados para reverter UI. Converter URLs somente com mapa de equivalência confirmado. Sitemap usa versão pública e exclui rascunhos/arquivadas. Purga de cache só das páginas afetadas, nunca de sessões globais.

## Estimativa e escopo
Executar por gates, sem prazo artificial antes de dimensionar conteúdo, API e infraestrutura. Cada etapa deve resultar em commit revisável com documentação e testes relevantes. O banco temporário e ferramentas locais anteriores não devem entrar por acidente na publicação.
