# Validação e liberação da atualização SEO

Status: suíte local SEO aprovada (17 testes, 52 verificações); regressão completa ainda pendente. Evidências em [resultados](validation-results.md).

## Preparação
- Usar instalação local/homologação com banco exclusivo e usuário limitado a esse banco. O grupo tests existe, mas a configuração atual usa root: não executar testes com migrations/refresh antes de conferir isolamento e sobrescritas de ambiente.
- Usar dados fictícios para usuários, pedidos, cupons, fotos e ensaios. Não copiar dados pessoais ou credenciais de produção.
- Verificar dependências pelo composer.lock, sem atualizar versões durante esta entrega. PHPUnit executa com leitura autorizada no Windows; o bootstrap existe. Consultar validation-results.md.
- Integrar pagamentos em sandbox e substituir e-mail, agenda, armazenamento e callbacks por adaptadores de teste. Nunca cobrar, enviar mensagens, reservar agenda real ou apagar arquivos externos em testes.
- Homologação deve exigir autenticação de acesso e responder noindex; não cadastrar seu sitemap no Google. Confirmar a remoção dessas restrições somente no domínio público de produção.

## Matriz de regressão
| Área | Cenário | Aceitação |
|---|---|---|
| Home | Visitante anônimo e usuário autenticado | Conteúdo e redirecionamentos esperados, sem erro PHP |
| SEO | Home, investimento, ensaio e acervo | Title/description corretos, canonical absoluto, JSON-LD válido |
| Sitemap | Conteúdo publicado e não publicado | XML válido, URLs oficiais, investimento presente, privados ausentes |
| Ensaios | Publicado, despublicado, inexistente | 200, 404, 404 respectivamente |
| Agendamento | Landing e seleção de horário | Noindex; funcionamento preservado; nenhuma reserva real |
| Acervo | Coleção, anterior/próxima, parâmetros e compartilhamento | Fotografia correta, navegação e cartão social preservados |
| Autenticação | Login, logout, retorno e permissões | Retorno correto, sessão íntegra, acesso indevido negado |
| Administração | Listar, criar, editar, publicar/despublicar | Persistência correta em dados fictícios e áreas protegidas |
| Arquivos | Upload e seleção de capa | Arquivo de teste válido, original preservado e sem acesso ao bucket real |
| Compra | Pacote, cupom válido/inválido e total | Preços e descontos corretos em sandbox |
| Pagamento | Aprovado, falha, pendente, webhook repetido | Estado correto, repetição idempotente e ausência de cobrança real |
| Cliente | Galeria e pedidos de outro cliente | Usuário só acessa seus próprios registros |
| Legal | Privacidade e termos | Páginas acessíveis e links corretos |
| Visual | Celular e desktop, menu, carrossel, modal e formulários | Sem sobreposição, corte de texto ou controles inoperantes |

## Automação
1. Verificação de sintaxe e diff em cada alteração (já realizada nesta entrega).
2. Testes unitários de política de resposta e funções isoladas.
3. Testes HTTP com fixtures e banco isolado para status, acesso, metadados, cabeçalhos e sitemap. Atualizar expectativas antigas da home antes de usar a suíte como critério de liberação.
4. Testes de navegador com dados descartáveis para percursos públicos/admin e captura de telas nas larguras 390 e 1440 pixels. Comparar com baseline anterior à atualização.
5. Simular integrações e executar contrato sandbox separadamente. Não confundir teste simulado com confirmação de integração real.

## Evidências e bloqueios
Cada execução deve registrar data, revisão do código, ambiente, cenário, resultado e evidência sem dados pessoais. Falhas devem distinguir regressão nova de problema anterior, mantendo ambos visíveis.
Não liberar com falha em autenticação, permissões, pagamento, persistência, sitemap ou fluxo principal. Testes não executados permanecem pendentes; não tratá-los como aprovados. Cobertura percentual não substitui os cenários da matriz.

## Publicação gradual e reversão
Guardar versão anterior e backup antes do deploy. Publicar em janela acompanhada, remover sitemap estático remoto e limpar apenas caches pertinentes. Conferir sem mutações: páginas públicas, login, sitemap e cabeçalhos. Conferir operação administrativa com registro fictício controlado. Monitorar erros, falhas de checkout e contatos. Reverter se houver regressão funcional; alterações de indexação levam tempo para refletir no Google.
Depois, verificar canonical/indexação no Search Console, validar dados estruturados, registrar PageSpeed e acompanhar métricas de 28/90 dias. Esses resultados são posteriores ao deploy e não podem ser garantidos pelos testes locais.
