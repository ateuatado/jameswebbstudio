# Modelo e contratos propostos

Nomes definitivos a validar na F0. Prefixo service_content evita colisão com services comerciais.

## Entidades
| Entidade | Campos/regras principais |
|---|---|
| service_content_families | id, nome, slug único, template_key permitido, descrição editorial, ativo, ordem |
| service_content_contexts | id, versão, fatos estruturados, aprovador/data, created_at; snapshot imutável |
| service_content_pages | id, family_id, parent_id opcional (um nível de modalidade), slug, status, current_revision_id, published_revision_id, lock_version, created_by, updated_at, published_at |
| service_content_revisions | id, page_id, schema_version, contexto, blocos JSON, metadados, mídia por IDs, expressões relacionadas, CTA, autor, origem manual/IA, modelo/prompt/context_version, created_at; imutável |
| service_content_jobs | id, batch_id, page_id, entrada normalizada, idempotency_key única, estado, lease_owner/expires_at, tentativas, limites e consumo, erro sanitizado, proposed_revision_id |
| service_content_batches | id, criador, quantidade, orçamento/moeda, reservado/consumido, estado/cancel_requested, timestamps |
| service_content_redirects | caminho antigo único, page_id destino atual; validação sem ciclo, resolver diretamente para URL canônica |
| service_content_audit | ator, ação, entidade/revisão, data, metadados mínimos; sem segredo ou mensagem pessoal |

Número WhatsApp e configuração não secreta reutilizam StudioSettings se o modelo comportar. Credencial da API fica no ambiente. Métricas agregadas opcionais em fase posterior, com retenção e consentimento definidos; não reutilizar tracking com IP sem revisão.

## Contrato de conteúdo v1
Objeto com title, h1, introduction, sections[{type, heading, body}], faq[{question,answer}], meta_description, whatsapp_message e fact_warnings[]. Types permitidos: overview, applications, process, deliverables, portfolio, contact. Mídia/URLs finais e slug são resolvidos no servidor com escolhas do admin. Não aceitar HTML livre. Limites de campos e de blocos definidos no validador; texto escapa na view. Nenhuma nota/avaliação, preço ou oferta estruturada é gerada sem fato aprovado.

## Rotas sugeridas
- Admin: GET /admin/paginas-servicos; POST criar/editar/gerar/revisar/publicar/arquivar em rotas explícitas com CSRF e autorização específica.
- Lotes: GET estado; POST preparar, confirmar, cancelar e repetir; nunca publicar por GET.
- Prévia: GET /admin/paginas-servicos/{id}/previa, permissão de edição/leitura, noindex e Cache-Control private,no-store.
- Público: GET /servicos, /servicos/{familia}, /servicos/{familia}/{modalidade}; registrar antes do catch-all de Hero. Famílias podem ser páginas/hubs com conteúdo próprio; não publicar lista vazia automaticamente.
- WhatsApp: link direto https://wa.me/{numero}?text={texto_codificado}; sem envio automático. JS de evento opcional não bloqueia o link.

## Estado e consistência
Job: queued → running → succeeded/failed/cancelled; retry apenas quando permitido. Retry com mesmo id não gera nova página; nova tentativa registra consumo potencialmente adicional. Publicação usa compare-and-swap da lock_version e transação, exigindo revisão aprovada correspondente. Bloquear publicação de texto desatualizado sem revisão da alteração.
Estado público depende de published_revision_id e status, nunca do último resultado da IA. Falha de fornecedor ou do worker não derruba o conteúdo já publicado. Contexto, slug e oferta mudados geram alerta de revalidação. Jobs excluídos por retenção preservam referência mínima da revisão sem conteúdo privado.
