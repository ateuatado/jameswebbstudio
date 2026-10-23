# Plano de atualização SEO

Protocolo detalhado: [validação e liberação](validation.md).

## 1. Correções locais
Manter arquitetura CodeIgniter e fluxos de venda/autenticação. Corrigir descoberta, publicação, metadados e dados estruturados; melhorar apresentação local e prioridade das imagens. Sem migração de banco, edição de segredos ou alterações no domínio Hero.

## 2. Verificação e publicação
1. Reparar dependências de desenvolvimento: instalação atual não contém o bootstrap de testes configurado. Executar teste NoIndexTest e testes de publicação com banco exclusivo de testes.
2. Validar 200 de páginas públicas, 404 de ensaios despublicados/inexistentes, XML válido e cabeçalhos noindex. Auditar rotas adicionais de autenticação.
3. Confirmar dados cadastrais e revisar páginas visualmente em homologação.
4. Comparar código com produção e conferir app.baseURL efetivo, hosts, HTTPS/www, proxy/CDN. Implementar 301 no servidor somente após conhecer infraestrutura, evitando loops.
5. Publicar e EXCLUIR o sitemap estático remoto; simples cópia de arquivos não remove o arquivo antigo. Conferir XML pela internet e limpar caches pertinentes.
6. Validar JSON-LD no Rich Results Test, canonical na inspeção de URL e enviar sitemap ao Search Console.

## 3. Conteúdo e presença local
Priorizar serviços por oferta e consultas reais. Preparar páginas e portfólios próprios, links internos, narrativas reais e contato confirmado. Revisar Perfil da Empresa e consistência dos dados. Inventariar URLs/tráfego do Hero; redirecionar somente páginas realmente migradas para equivalentes.

## 4. Desempenho
Medir URLs representativas no celular. Gerar derivados responsivos no upload/reprocessamento com limites de recursos, fallback e preservação do original. Agrupar consultas de serviços quando o custo for confirmado. Pré-gerar imagens sociais, preservando compatibilidade com arquivos antigos.

## 5. Resultados e reversão
Registrar baseline e datas de publicação. Comparar períodos de 28 dias e tendência de 90 dias considerando sazonalidade. Medir cliques sem marca, consultas comerciais, CTR por consulta/página, indexação e contatos/agendamentos orgânicos. Definir eventos e consentimento antes de instrumentar. Não usar posição média isolada como meta.
Reverter alterações por arquivo caso haja regressão; nenhuma migração de banco nesta fase. Não restaurar sitemap antigo como solução definitiva.
