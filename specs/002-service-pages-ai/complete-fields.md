# Correção: preencher seções e campos SEO no painel

Problema relatado: editor aberto antes do processamento, campos de busca vazios e erro "Informe entre 1 e 10 seções" ao salvar. A fila existia, mas o usuário precisava executar o worker para ver a página preenchida; campos SEO adicionais também eram opcionais no adaptador.

Comportamento corrigido:
- A ação administrativa individual de gerar cria/reutiliza o job e processa apenas a página solicitada, no painel, com o mesmo orçamento e claim atômico. Não é necessário terminal por página. Lotes continuam no worker em segundo plano.
- Ajuste explícito à decisão anterior de nunca gerar em requisição HTTP: somente a mutação administrativa autenticada com permissão/CSRF executa uma chamada limitada a 40s. Visitas públicas, GET, indexação e sitemap nunca disparam IA.
- Rascunho vazio não apresenta editor manual nem botão de salvar incompleto. Página na fila pode ser processada pelo botão "Gerar agora e preencher todos os campos".
- Resposta real DeepSeek só é aceita com expressões, intenção de busca e pelo menos duas seções, além dos campos obrigatórios. Revisões antigas continuam legíveis.
- Ao salvar revisão sem seções/expressões/intenção, operador com permissão de gerar recebe preenchimento automático: texto já escrito é preservado, campos ausentes vêm da IA e o resultado continua como rascunho. Se não houver configuração/limite/chave, informa falha sem publicar ou descartar a revisão existente.
- Conflito com edição concorrente não aplica resultado sobre a revisão de outra pessoa. Nenhuma repetição de cobrança é feita automaticamente em timeout.

Validação local: suíte com provedor falso, incluindo POST autenticado/CSRF que completa seções e SEO sem perder título manual. Não há nova migration. Exige atualizar arquivos no VPS e recarregar a tela; uma página previamente criada vazia precisa da ação explícita de gerar, não de preenchimento manual.
