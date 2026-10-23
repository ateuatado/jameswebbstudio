# Matriz de validação e liberação

## Condições
Unitários com provedor falso, sem rede/API paga. Integração em MariaDB descartável com usuário restrito e dados fictícios. Testes de fornecedor real separados, com teto de consumo configurado e credenciais no ambiente. Não enviar mensagens WhatsApp nem acionar checkout/agendamento em testes automáticos. Nunca executar migrations de teste no banco do site.

| Cenário | Evidência exigida |
|---|---|
| Acesso anônimo/comum/admin sem permissão | CRUD, prévia, geração e publicação protegidos; não basta esconder botões |
| CSRF | Mutações novas sem token recusadas; webhooks legados preservados |
| Rascunho e prévia | Não entram em sitemap; visitante recebe 404; prévia autenticada noindex/no-store |
| Publicação | 200, revisão aprovada, canonical correto, link interno e sitemap |
| Troca de slug/arquivo | 301 direto para destino, sem loop; 410 apenas se antes publicada |
| Concorrência editorial | Versão antiga não sobrescreve nova; restaurar cria publicação explícita |
| IA inventa oferta/preço/região | Bloqueio/alerta revisável; nada publicado automaticamente |
| Tema contém instrução maliciosa | Sem execução, leitura de segredo, HTML arbitrário ou URL externa inventada |
| Timeout/429/credencial inválida/JSON quebrado | Falha recuperável, custo incerto indicado e edição preservada |
| Duplicatas e agrupamento | Prévia sinaliza sobreposição; não apaga nem publica sem decisão |
| Worker duplicado/lease expirado | Claim atômico e idempotência, sem páginas/revisões duplicadas |
| Lote cancelado e orçamento concorrente | Não inicia itens cancelados/não provisionados; limite não ultrapassado por reserva local |
| WhatsApp | Número e texto codificados; serviço/URL certos; funciona sem JS; clique não é venda |
| Fotografias | IDs autorizados, derivados responsivos, sem acesso a originais privados |
| Desempenho | Nenhuma chamada de IA na visita; consulta paginada; medir LCP/CLS e bytes |
| Regressão | Home, ensaios, obras, login, painel, pacotes/checkout e agenda preservados |

## Piloto
Três serviços distintos, conteúdos revisados com responsável. Verificar textos, fatos, mídia, formulário/editor, menu, navegação por teclado, contraste, WhatsApp e telas 390/1440 px. Registrar cenário, revisão Git, ambiente e resultado. Conferir PHP 8.2/8.4. Não marcar item não executado como aprovado.

## Liberação e acompanhamento
Backup restaurável do banco e código antes de migrations; feature flag; ativar apenas piloto aprovado. Operador aplica pelo Git no VPS e configura worker/segredo fora do repositório. Monitorar erros, fila/custos e conteúdo duplicado; rollback não apaga revisões. Validar sitemap e inspeção de URLs no Search Console. Comparar consultas e contatos em períodos equivalentes, sem prometer indexação/posição. Relatar limites de atribuição e de dados reais de experiência.
