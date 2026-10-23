# Desempenho — segunda entrega

Origem: relatório Lighthouse enviado em 23/10/2026: desempenho 86, FCP/LCP 3,1s, payload ~9 MB, estimativa de economia de imagens ~8,5 MB, debugbar pública e fontes/CSS externos bloqueantes. São dados de laboratório, não experiência real de usuários.

## Implementação
- Bootstrap 5.3.2 passa a ser servido localmente, mantendo versão e ordem de execução para não quebrar scripts inline. CSS permanece bloqueante para preservar aparência inicial. Não se afirma eliminar todo o CSS crítico.
- Google Fonts carregado como preload de estilo com fallback noscript; pode existir troca visual de fonte, a medir novamente no Lighthouse.
- Logo WebP em 660x144, exibido à altura anterior com dimensões declaradas; original preservado.
- Barra de depuração somente em development com host local ou .test. Produção deve também configurar CI_ENVIRONMENT=production; o filtro não substitui essa configuração.
- Capas: comando media:optimize-covers cria WebP 480/800/1200/1600 quando menores que a origem, qualidade 84, sem cortes. Arquivos identificados por caminho/mtime/tamanho, idempotentes, escrita temporária e publicação por rename. Nenhuma geração na visita pública.
- Home usa srcset/sizes e dimensões reais; sem derivados mantém original. Só imagens dentro de uploads/heroes são aceitas; limite 30 MP. JPEG com orientação EXIF especial é mantido original e reportado para normalização posterior.

## Publicação manual
1. Backup e fast-forward pelo Git como na entrega anterior. Não executar migrations nem tocar .env local.
2. No VPS, verificar GD/WebP e executar como proprietário do site: `sudo -u jameswebbstudio php -d memory_limit=256M spark media:optimize-covers`. Ajustar o usuário somente se a propriedade real for outra. Esse comando lê capas publicadas no banco e escreve apenas derivados; não altera fotos ou registros.
3. Após novas capas/substituições, repetir o comando. Automação no upload ainda não implementada.
4. Conferir thumbnails e fotos, desktop/celular, menu, fonte e console; verificar ausencia de debugbar no HTML e srcset nas capas. Confirmar production no ambiente do servidor sem expor credenciais.
5. Repetir PageSpeed na mesma URL/perfil móvel, registrar peso e FCP/LCP/CLS; não prometer nota específica. Ajustar cache na configuração Nginx/CDN somente após inspecioná-la, sem regra global de cache para HTML autenticado.

## Validação local
19 testes/62 assertions aprovadas na suíte local, incluindo derivados WebP, dimensões, repetição, preservação do original, rejeição de caminho e ausência de injeção da toolbar. Não houve execução no VPS nem avaliação visual pública desta segunda entrega.

## Pendências
Detalhes do Lighthouse sobre links não rastreáveis e contraste ainda aguardados. Normalização EXIF, integração do otimizador no upload e cache de mídia no Nginx são evoluções separadas.
