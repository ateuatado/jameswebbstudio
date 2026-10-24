# Processamento e progresso pelo painel

Problema observado: interface já atualizada, mas lotes continuavam com aparência de aguardando; banco local tinha 25 jobs queued, 5 succeeded e 50 cancelled. Recarregar não processa jobs e não deve disparar chamadas de IA.

Implementado: painel deriva progresso dos estados reais dos jobs em consulta agregada, em vez de confiar no status histórico do lote. Botão Processar lote com IA inicia até 25 requisições administrativas sequenciais, uma chamada por item, com CSRF renovado, limite de consumo e claim atômico. Botão Processar um permite teste isolado. Exibe progresso, consumo contabilizado e uso informado a cada resposta, sem terminal. Para em falha, limite, solicitação do usuário ou ausência de item elegível. Não repete requisição de resultado incerto automaticamente. Nenhuma publicação ocorre no processamento.

É preciso manter a tela aberta para continuar o lote; fechar/navegar interrompe novos pedidos, mas uma chamada já enviada pode terminar e consumir. Worker CLI permanece disponível para operação independente do navegador. Sem JavaScript a resposta individual é JSON; processamento assistido em lote usa navegador com JavaScript habilitado. API jamais é acionada por GET/visita pública.

Números antigos de reserva não são recalculados retroativamente sem prova de consumo por tentativa. Atualizar painel mostra estados recentes; atualizar código não cria chamadas nem apaga histórico. Nenhuma migration necessária.
