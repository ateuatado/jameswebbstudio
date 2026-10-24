# Consumo confirmado e diagnóstico de limites

Problema: cada chamada bem-sucedida mantinha a reserva máxima inteira, bloqueando geração muito antes do limite de consumo real. Mensagem genérica também confundia falha diária, teto de lote, cancelamento e erro técnico.

Correção: na mesma transação que conclui o job e registra a revisão, devolver a diferença entre reserva e consumo informado para o dia e lote. Chamadas sem uso informado, falhas e timeouts mantêm proteção porque podem ter sido cobrados. A transição de estado do job impede liquidação duplicada. Reservas históricas anteriores não são apagadas nem recalculadas sem evidência de cada tentativa.

Erros agora distinguem token diário, orçamento diário em USD, teto do lote e lote cancelado. Falha técnica antes da chamada não afirma que houve consumo. `service-pages:status` informa limites efetivos e saldo contabilizado/protegido do dia sem segredo.

Caso observado: captura com URL .test; local permanecia com limite de 100000, enquanto o usuário havia ajustado somente o VPS. Local atualizado para 2000000 e USD 1 conforme instrução já dada. Não houve alteração remota automática nem chamada real à API nesta correção.

Teste: duas chamadas simuladas de 600 tokens sob limite de 21000 completam; registro final é 1200 tokens e USD 0.001080 estimados, em vez de duas reservas de 19500. Nenhuma migration necessária.
