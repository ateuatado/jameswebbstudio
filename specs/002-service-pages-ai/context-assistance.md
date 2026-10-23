# Ajuste de usabilidade: IA primeiro, aprovação depois

Solicitação: evitar preenchimento manual de campos/base para geração de centenas de páginas.

Implementação: botão "Gerar informações do estúdio com IA" no painel. Cria job de contexto no mesmo worker, orçamento, cancelamento e limite das páginas. A IA recebe nomes de serviços cadastrados e contexto já aprovado, se houver; sem esse contexto, usa identificação do estúdio e condições a combinar no briefing, sem preços/prazos/localização inventados. Resultado fica em tabela própria de propostas, não em contextos aprovados nem em páginas públicas.

O formulário é preenchido com a proposta. Operador confere, confirma WhatsApp e aprova. A base é reutilizada em todos os lotes seguintes. Regeneração não altera a base aprovada nem páginas existentes sem ação explícita. A IA continua preenchendo todos os campos textuais por página; imagens são escolhidas do portfólio autorizado. Pendências só devem bloquear quando houver contradição/fato indispensável, não por ausência de preço/prazo opcional.

Atualização: `php spark service-pages:install` aplica somente as migrations novas do módulo, idempotentemente. O processamento continua em `php spark service-pages:work 1` ou no worker periódico já configurado. Nenhuma chamada à IA ocorre na visita pública.
