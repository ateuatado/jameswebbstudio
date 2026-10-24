<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<h1>Páginas de serviços</h1>
<p>Crie páginas de ofertas reais, revise o conteúdo e publique. A geração por IA cria somente rascunhos.</p>
<p class="text-muted">Páginas públicas: <?= $configuration->enabled ? 'habilitadas' : 'desabilitadas no ambiente' ?> · Geração: <?= $configuration->generationEnabled ? 'habilitada' : 'desabilitada' ?>. Limites diários reservados: <?= (int) $configuration->dailyTokenLimit ?> tokens e US$ <?= number_format($configuration->dailyUsdLimit, 2) ?>. Estimativas conservadoras; confira a cobrança na conta do fornecedor.</p>
<section class="card p-3 mb-4"><h2 class="h4">A IA prepara, você aprova</h2><p>Não precisa escrever a apresentação do estúdio. Gere uma proposta a partir dos serviços cadastrados, confira e aprove uma vez. Essa base será reutilizada em todas as páginas; a IA também preenche títulos, textos, perguntas e metadados de cada tema.</p>
<form method="post" action="<?= site_url('admin/paginas-servicos/contexto/sugerir') ?>"><?= csrf_field() ?><input type="hidden" name="key" value="<?= esc($contextKey) ?>"><button class="btn btn-primary" <?= $configuration->generationEnabled ? '' : 'disabled' ?>>Gerar informações do estúdio com IA</button></form><p class="small mt-2 mb-0">A proposta aparecerá abaixo quando o processamento terminar. Você só precisa confirmar o WhatsApp e aprovar as informações.</p></section>
<details class="card p-3 mb-4" <?= $suggestion ? 'open' : '' ?>><summary><?= $suggestion ? 'Proposta da IA pronta para sua aprovação' : 'Informações do estúdio e WhatsApp' ?></summary>
<form method="post" action="<?= site_url('admin/paginas-servicos/contexto') ?>" class="mt-3">
<?= csrf_field() ?>
<?php if ($suggestion): ?><input type="hidden" name="suggestion_id" value="<?= (int) $suggestion['id'] ?>"><?php $proposalWarnings = json_decode($suggestion['warnings'] ?? '[]', true) ?? []; if ($proposalWarnings): ?><div class="alert alert-info">Pontos para conferir: <?= esc(implode(' · ', $proposalWarnings)) ?></div><?php endif; ?><?php endif; ?>
<label for="facts" class="form-label">Base reutilizável para as páginas — ajustes são opcionais</label>
<textarea id="facts" name="facts" rows="8" maxlength="10000" class="form-control" required><?= esc($suggestion['facts'] ?? $context['facts'] ?? '') ?></textarea>
<label for="whatsapp" class="form-label mt-3">WhatsApp com DDI</label>
<input id="whatsapp" name="whatsapp" class="form-control" value="<?= esc($context['whatsapp'] ?? '') ?>" placeholder="5511999999999" required>
<p class="mt-2">Aprovar uma vez permite reutilizar esta base em centenas de páginas. Páginas já publicadas não mudam automaticamente.</p><?php if ($suggestion): ?><label class="d-block mb-3"><input type="checkbox" name="approved" value="1" required> Conferi e aprovo esta proposta como informações do estúdio.</label><?php endif; ?><button class="btn btn-success"><?= $suggestion ? 'Aprovar e usar em todas as próximas páginas' : 'Salvar informações aprovadas' ?></button>
</form></details>
<details class="card p-3 mb-4"><summary>Adicionar família de serviços</summary>
<form method="post" action="<?= site_url('admin/paginas-servicos/familia') ?>" class="mt-3"><?= csrf_field() ?>
<label for="family-name">Nome (ex.: Fotografia de produtos)</label><input id="family-name" name="name" maxlength="150" class="form-control mb-3" required>
<label for="family-description">Apresentação pública da família</label><textarea id="family-description" name="description" maxlength="2500" class="form-control mb-3" required></textarea><button class="btn btn-primary">Cadastrar família</button></form></details>
<section class="card p-3 mb-4"><h2 class="h4">Criar páginas individuais ou em lote</h2>
<form method="post" action="<?= site_url('admin/paginas-servicos/preparar') ?>"><?= csrf_field() ?>
<label for="family">Família</label><select id="family" name="family_id" class="form-select mb-3" required><option value="">Selecione</option><?php foreach ($families as $f): ?><option value="<?= (int) $f['id'] ?>"><?= esc($f['name']) ?></option><?php endforeach; ?></select>
<label for="topics">Temas, um por linha (até 25)</label><textarea id="topics" name="topics" rows="5" maxlength="10000" class="form-control mb-3" placeholder="Fotografia de joias&#10;Fotografia para e-commerce" required></textarea><button class="btn btn-primary">Revisar lista</button></form></section>
<?php if ($preview): ?>
<section class="card p-3 mb-4"><h2 class="h4">Confirme os temas</h2><ul><?php foreach ($preview['rows'] as $row): ?><li><?= esc($row['topic']) ?> — <?= $row['duplicate'] ? 'já existe/repetido: será ignorado' : esc($row['slug']) ?></li><?php endforeach; ?></ul>
<p>Expressões semelhantes podem representar a mesma necessidade. Revise a lista antes de criar várias páginas. Novos temas não confirmam oferta.</p>
<p>IA: reserva máxima estimada por tentativa US$ <?= number_format(($configuration->maxInputTokens * $configuration->inputUsdPerMillion + $configuration->maxOutputTokens * $configuration->outputUsdPerMillion) / 1000000, 4) ?>; até <?= (int) $configuration->maxAttempts ?> tentativas por item. O teto diário pode interromper o lote. Repetições exigem nova confirmação.</p>
<form method="post" action="<?= site_url('admin/paginas-servicos/lote') ?>"><?= csrf_field() ?><input type="hidden" name="family_id" value="<?= (int) $preview['family_id'] ?>"><input type="hidden" name="key" value="<?= esc($preview['key']) ?>">
<?php foreach ($preview['topics'] as $topic): ?><input type="hidden" name="topics[]" value="<?= esc($topic) ?>"><?php endforeach; ?>
<label class="d-block mb-3"><input type="checkbox" name="generate" value="1" <?= $configuration->generationEnabled ? 'checked' : 'disabled' ?>> Criar conteúdo completo com IA para todos os novos temas (usa a API; publicação depende da sua aprovação)</label><button class="btn btn-success">Confirmar e preparar páginas</button></form></section>
<?php endif; ?>
<h2 class="h4">Páginas</h2><p><?= (int) $totalPages ?> cadastradas · página <?= (int) $pageNumber ?>. <?php if ($pageNumber > 1): ?><a href="<?= site_url('admin/paginas-servicos') . '?pagina=' . ($pageNumber - 1) ?>">Anterior</a><?php endif; ?> <?php if ($pageNumber * 50 < $totalPages): ?><a href="<?= site_url('admin/paginas-servicos') . '?pagina=' . ($pageNumber + 1) ?>">Próxima</a><?php endif; ?></p><div class="table-responsive"><table class="table"><thead><tr><th>Tema</th><th>Estado</th><th>Revisão pública</th><th>Ação</th></tr></thead><tbody>
<?php foreach ($pages as $p): ?><tr><td><?= esc($p['topic']) ?></td><td><?= esc($p['status']) ?></td><td><?= (int) $p['published_revision_id'] ?></td><td><a href="<?= site_url('admin/paginas-servicos/' . $p['id']) ?>">Editar e revisar</a></td></tr><?php endforeach; ?></tbody></table></div>
<details class="card p-3 mb-4"><summary>Publicar páginas revisadas em lote</summary><form method="post" action="<?= site_url('admin/paginas-servicos/publicar-lote') ?>" class="mt-3"><?= csrf_field() ?><?php foreach ($pages as $p): if (! $p['current_revision_id']) continue; ?><label class="d-block mb-2"><input type="checkbox" name="pages[<?= (int) $p['id'] ?>]" value="<?= (int) $p['lock_version'] ?>"> <?= esc($p['topic']) ?> (revisão <?= (int) $p['current_revision_id'] ?>)</label><?php endforeach; ?><label class="d-block my-3"><input type="checkbox" name="reviewed" value="1" required> Revisei e aprovo individualmente o conteúdo, as imagens e a oferta das páginas selecionadas.</label><button class="btn btn-success">Publicar selecionadas</button></form></details>
<h2 class="h4">Lotes recentes</h2><p>Os números de consumo são históricos. Para gerar os rascunhos pendentes, use “Processar lote”. Mantenha esta tela aberta enquanto processa; nada será publicado.</p><a href="<?= site_url('admin/paginas-servicos') ?>" class="btn btn-outline-secondary mb-3">Atualizar painel</a>
<div class="table-responsive"><table class="table"><thead><tr><th>Lote</th><th>Progresso</th><th>Consumo + proteção / teto</th><th>Tokens informados</th><th>Ações</th></tr></thead><tbody><?php foreach ($batches as $b): ?><tr><td><?= (int) $b['id'] ?></td><td data-progress><?= esc(['queued' => 'Aguardando', 'running' => 'Processando', 'completed' => 'Concluído', 'failed' => 'Com falhas', 'cancelled' => 'Cancelado'][$b['status']] ?? $b['status']) ?><br><?= (int) $b['done'] ?> prontos · <?= (int) $b['pending'] ?> pendentes · <?= (int) $b['failed'] ?> falhas</td><td data-budget><?= (int) $b['reserved_tokens'] ?> / <?= (int) $b['token_limit'] ?></td><td data-used><?= (int) $b['used_tokens'] ?></td><td>
<?php if ($b['pending'] && $b['status'] !== 'cancelled'): ?><form class="process-batch mb-2" method="post" action="<?= site_url('admin/paginas-servicos/lotes/' . $b['id'] . '/processar') ?>" data-pending="<?= (int) $b['pending'] ?>"><?= csrf_field() ?><button name="quantity" value="25" class="btn btn-sm btn-primary">Processar lote com IA</button> <button name="quantity" value="1" class="btn btn-sm btn-outline-primary">Processar um</button><button type="button" class="stop-batch btn btn-sm btn-outline-warning d-none">Parar após este item</button><p class="batch-progress small mt-2" role="status"></p></form><?php endif; ?>
<?php if ($b['pending'] && $b['status'] !== 'cancelled'): ?><form method="post" action="<?= site_url('admin/paginas-servicos/lotes/' . $b['id'] . '/cancelar') ?>"><?= csrf_field() ?><button class="btn btn-sm btn-outline-danger">Cancelar pendentes</button></form><?php endif; ?></td></tr><?php endforeach; ?></tbody></table></div>
<h2 class="h4">Fila de geração</h2><p>Atualize a tela para acompanhar. Itens aguardam execução do worker pelo operador. Reservas são conservadoras; falhas podem ter consumo não informado.</p><div class="table-responsive"><table class="table"><thead><tr><th>Item/página</th><th>Estado</th><th>Resultado</th><th>Repetição</th></tr></thead><tbody><?php foreach ($jobs as $j): ?><tr><td>#<?= (int) $j['id'] ?> / <a href="<?= site_url('admin/paginas-servicos/' . $j['page_id']) ?>"><?= (int) $j['page_id'] ?></a></td><td><?= esc($j['state']) ?></td><td><?= esc($j['error'] ?? '') ?><?= $j['revision_id'] ? ' Revisão #' . (int) $j['revision_id'] : '' ?></td><td><?php if ($j['state'] === 'failed'): ?><form method="post" action="<?= site_url('admin/paginas-servicos/itens/' . $j['id'] . '/repetir') ?>"><?= csrf_field() ?><label><input type="checkbox" name="confirm_cost" value="1" required> Aceito novo consumo</label><button class="btn btn-sm btn-outline-warning">Repetir</button></form><?php endif; ?></td></tr><?php endforeach; ?></tbody></table></div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
document.querySelectorAll('.process-batch').forEach(form => {
    let stop = false;
    form.querySelector('.stop-batch').addEventListener('click', () => { stop = true; });
    form.addEventListener('submit', async event => {
        event.preventDefault();
        const limit = Math.min(Number(event.submitter?.value || 1), Number(form.dataset.pending), 25);
        const output = form.querySelector('.batch-progress');
        const stopButton = form.querySelector('.stop-batch');
        const buttons = [...document.querySelectorAll('button[type="submit"], button:not([type])')];
        const disabledBefore = buttons.map(button => button.disabled);
        buttons.forEach(button => button.disabled = true);
        stop = false; stopButton.classList.remove('d-none');
        try {
            for (let count = 0; count < limit && !stop; count++) {
                output.textContent = `A IA está gerando o item ${count + 1} de até ${limit}…`;
                const response = await fetch(form.action, { method: 'POST', body: new FormData(form), credentials: 'same-origin', headers: { Accept: 'application/json' } });
                if (!response.ok || !(response.headers.get('content-type') || '').includes('application/json')) throw new Error('A sessão ou a requisição expirou. Atualize o painel antes de continuar.');
                const result = await response.json();
                document.querySelectorAll('input[type="hidden"]').forEach(input => { if (input.name === result.csrf_name) input.value = result.csrf_hash; });
                if (result.summary) {
                    const row = form.closest('tr'), summary = result.summary;
                    row.querySelector('[data-progress]').textContent = `${summary.done} prontos · ${summary.pending} pendentes · ${summary.failed} falhas`;
                    row.querySelector('[data-budget]').textContent = `${summary.reserved_tokens} / ${summary.token_limit}`;
                    row.querySelector('[data-used]').textContent = summary.used_tokens;
                    form.dataset.pending = summary.pending;
                }
                if (result.error) throw new Error(result.error);
                if (!result.processed) { output.textContent = 'Não há item pronto para processar agora. Repetições podem aguardar 30 segundos.'; return; }
                output.textContent = `${count + 1} item(ns) processado(s). Atualize o painel para abrir os resultados.`;
            }
        } catch (error) { output.textContent = error.message || 'Falha de conexão. Confira o resultado antes de repetir.'; }
        finally { buttons.forEach((button, index) => button.disabled = disabledBefore[index]); stopButton.classList.add('d-none'); }
    });
});
</script>
<?= $this->endSection() ?>
