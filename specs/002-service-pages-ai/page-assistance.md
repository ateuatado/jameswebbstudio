# Criação de páginas: IA como ação principal

Solicitação: gerar automaticamente também o texto e os elementos SEO de cada página de captação, sem preencher formulários extensos.

Implementado: botão de criação completa no topo do editor vazio; geração em lote selecionada por padrão quando habilitada, com confirmação explícita e aviso de consumo. Editor manual recolhido em Ajustes opcionais. Após o worker, página mostra uma apresentação legível para revisão e aprovação, além da prévia visual. Exibe fila/erro em vez de insinuar que campos vazios precisam ser preenchidos.

Contrato aditivo compatível com revisões antigas: keywords (até 15 expressões), search_intent e cta_label; valores ausentes recebem defaults. Prompt pede conteúdo orientado a orçamento pelo WhatsApp e uso natural das expressões no título, descrição e texto. Expressões são hipóteses editoriais da IA, sem volume comprovado; não criam meta keywords nem promessa de ranking. Mensagem WhatsApp, FAQs, seções e metadados continuam gerados junto ao restante do conteúdo. Formulário e publicação separados preservam revisão humana e versão pública anterior.

Nenhuma migration necessária nesta atualização. Worker continua obrigatório; configure processamento periódico no servidor para evitar comandos manuais por página. Nenhuma chamada IA é disparada pela visita pública. Fotografias continuam opcionais e selecionadas pelo operador a partir de material autorizado.
