# Tarefas de implementação

Versão inicial implementada localmente. Checklist abaixo distingue entregas realizadas das dependências de piloto e expansões. Operação/limites em quickstart.md.

## F0 — Definição
- [x] T001 Inventariar rotas, permissões, StudioSettings e vínculos de mídia.
- [ ] T002 Confirmar três serviços piloto, WhatsApp e fatos comerciais.
- [ ] T003 Escolher fornecedor/modelo, teto de custos e retenção; manter falso até configuração.
- [ ] T004 Revisar modelo/contratos e critérios com a spec; registrar decisões posteriores.

## F1 — Editorial
- [x] T005 Criar migrations aditivas e testar em MariaDB descartável.
- [x] T006 Repositório de domínio, validação de famílias/slugs e snapshots de contexto/revisão.
- [x] T007 Permissões granulares, filtros e CSRF em mutações novas.
- [x] T008 CRUD, editor, prévia autenticada e controle de concorrência.

## F2 — Site
- [x] T009 Templates de famílias e modalidades com layout e acessibilidade existentes.
- [x] T010 Publicação/revisão/restauração/arquivamento transacionais e com controle de versão.
- [x] T011 Rotas antes do catch-all, redirecionamentos e 404/410.
- [x] T012 Navegação/hubs, canonical, metadados e sitemap seletivo.
- [x] T013 Mídia de ensaios publicados com conferência humana de autorização e CTA WhatsApp sem JS.

## F3 — IA
- [x] T014 Interface independente e provedores falsos nos testes.
- [x] T015 Contexto versionado, contrato JSON v1 e prompt restritivo; fatos exigem revisão humana.
- [x] T016 DeepSeek com timeouts, segredo no ambiente e erros sanitizados; smoke real aprovado.
- [ ] T017 Ampliar testes de respostas HTTP/JSON truncado e revisão de alucinações; HTML e preservação de edição já testados.

## F4 — Lotes
- [ ] T018 Entrada por linhas, normalização, sugestão de agrupamento/duplicatas e revisão.
- [x] T019 Jobs/worker CLI, claims atômicos, lease de 180s e cancelamento; chamada limitada a 65s dispensa heartbeat nesta versão.
- [x] T020 Limites/reserva de custo/tokens, idempotência e repetição manual limitada.
- [x] T021 Painel de progresso/falhas e novas revisões sem sobrescrever publicação.
- [x] T022 Aprovação explícita e validação individual para publicação coletiva.

## F5 — Piloto e entrega
- [ ] T023 Executar matriz de validação e regressões legadas em ambiente isolado.
- [ ] T024 Revisar e publicar localmente três páginas piloto; verificar móvel/desktop.
- [ ] T025 Preparar backup/restore de banco, feature flag e roteiro VPS via Git.
- [ ] T026 Operador configura segredo, worker e WhatsApp no servidor; validar sem mensagens reais.
- [ ] T027 Smoke tests e ativação seletiva; Search Console e PageSpeed das páginas.

## F6 — Expansão
- [ ] T028 CSV seguro, edição coletiva como revisão e política de métricas/consentimento.
- [ ] T029 Avaliar qualidade, custo e sobreposição antes de elevar limites.
