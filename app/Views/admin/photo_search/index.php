<?= $this->extend('admin/layout') ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --gold: #c5a059;
        --gold-dim: rgba(197,160,89,0.15);
        --card-bg: #181818;
        --border: rgba(255,255,255,0.07);
    }
    .search-hero {
        background: linear-gradient(135deg, #0f0f0f 0%, #1a1510 100%);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .search-input-wrapper {
        position: relative;
    }
    .search-input-wrapper i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gold);
        font-size: 1.1rem;
    }
    .search-input {
        background: #0f0f0f !important;
        border: 1px solid rgba(197,160,89,0.3) !important;
        color: #fff !important;
        padding: 0.85rem 1rem 0.85rem 2.8rem !important;
        border-radius: 10px !important;
        font-size: 1.05rem !important;
        transition: all 0.3s ease;
    }
    .search-input:focus {
        border-color: var(--gold) !important;
        box-shadow: 0 0 0 3px rgba(197,160,89,0.15) !important;
    }
    .btn-search {
        background: var(--gold);
        color: #000;
        font-weight: 700;
        border: none;
        padding: 0.85rem 2rem;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    .btn-search:hover {
        background: #d4b06a;
        transform: translateY(-1px);
    }

    /* Grid de Resultados */
    .results-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.25rem;
        align-items: start;
    }
    .result-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    .result-card:hover {
        border-color: var(--gold);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.4);
    }
    .result-card img {
        width: 100%;
        height: auto;
        max-height: 280px;
        object-fit: contain;
        background: #000;
        display: block;
    }
    .result-card-body {
        padding: 10px 12px;
    }
    .project-badge {
        background: var(--gold-dim);
        color: var(--gold);
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 2px 8px;
        border-radius: 20px;
        border: 1px solid rgba(197,160,89,0.25);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        display: inline-block;
    }
    .filename-small {
        font-size: 0.72rem;
        color: #888;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 4px;
    }
    .tags-row {
        margin-top: 6px;
        display: flex;
        flex-wrap: wrap;
        gap: 3px;
    }
    .tag-pill {
        background: #222;
        color: #aaa;
        font-size: 0.58rem;
        padding: 1px 6px;
        border-radius: 10px;
        border: 1px solid #333;
    }
    .tag-pill.highlight {
        background: rgba(197,160,89,0.2);
        color: var(--gold);
        border-color: rgba(197,160,89,0.4);
    }

    /* Empty/loading states */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #555;
    }
    .empty-state i {
        font-size: 3.5rem;
        margin-bottom: 1rem;
        color: #333;
    }

    /* Paginação */
    .pagination .page-link {
        background: var(--card-bg);
        border-color: var(--border);
        color: #aaa;
    }
    .pagination .page-item.active .page-link {
        background: var(--gold);
        border-color: var(--gold);
        color: #000;
    }
    .pagination .page-link:hover {
        background: #222;
        color: var(--gold);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <h2 class="text-white mb-0" style="font-family:'Outfit',sans-serif;">🔍 Busca Global de Fotos</h2>
    <small class="text-muted ms-2">Pesquise por elemento, tag da IA ou nome do arquivo em <strong>todos os ensaios</strong></small>
</div>

<div class="search-hero">
    <form method="get" action="<?= site_url('admin/busca') ?>">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md">
                <div class="search-input-wrapper">
                    <i class="fas fa-search"></i>
                    <input
                        type="text"
                        name="q"
                        id="globalSearchInput"
                        class="form-control search-input"
                        placeholder="Ex: banana, vestido azul, óculos, piano, sorrindo..."
                        value="<?= esc($q) ?>"
                        autofocus
                    >
                </div>
            </div>
            <div class="col-12 col-md-auto">
                <button type="submit" class="btn btn-search w-100">
                    <i class="fas fa-search me-2"></i>Buscar em Todos os Ensaios
                </button>
            </div>
        </div>
    </form>
    <div class="mt-2 small text-muted">
        <i class="fas fa-brain me-1 text-warning"></i>
        A busca percorre as tags e descrições geradas pela IA em todos os projetos de clientes.
    </div>
</div>

<?php if (!empty($q)): ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <?php if ($total > 0): ?>
                <span class="text-muted">
                    Encontrado <strong class="text-white"><?= $total ?></strong> foto<?= $total !== 1 ? 's' : '' ?>
                    para <strong class="text-warning">"<?= esc($q) ?>"</strong>
                </span>
            <?php else: ?>
                <span class="text-muted">Nenhuma foto encontrada para <strong class="text-warning">"<?= esc($q) ?>"</strong></span>
            <?php endif ?>
        </div>
        <?php if ($totalPages > 1): ?>
            <small class="text-muted">Página <?= $page ?> de <?= $totalPages ?></small>
        <?php endif ?>
    </div>
<?php endif ?>

<?php if (!empty($q) && empty($results)): ?>
    <div class="empty-state">
        <i class="fas fa-search"></i>
        <p class="mt-2">Nenhuma foto encontrada com essa tag ou descrição.</p>
        <p class="small text-muted">Lembre-se: as tags só aparecem em fotos processadas pela IA após o upload.</p>
    </div>

<?php elseif (!empty($results)): ?>
    <div class="results-grid" id="resultsGrid">
        <?php foreach ($results as $photo): ?>
            <a href="<?= site_url('admin/client-projects/' . $photo->project_id . '/photos') ?>" class="text-decoration-none" target="_blank">
                <div class="result-card">
                    <img src="<?= esc($photo->presigned_url) ?>" alt="<?= esc($photo->original_filename) ?>" loading="lazy">
                    <div class="result-card-body">
                        <div class="project-badge" title="<?= esc($photo->project_name) ?>">
                            📁 <?= esc($photo->project_name) ?>
                        </div>
                        <div class="filename-small"><?= esc($photo->original_filename) ?></div>
                        <?php if (!empty($photo->ai_tags)): ?>
                            <div class="tags-row">
                                <?php
                                $tags = array_slice(explode(',', $photo->ai_tags), 0, 8);
                                $qLower = strtolower(trim($q));
                                foreach ($tags as $tag):
                                    $tag = trim($tag);
                                    if (!$tag) continue;
                                    $isMatch = str_contains(strtolower($tag), $qLower);
                                ?>
                                    <span class="tag-pill <?= $isMatch ? 'highlight' : '' ?>"><?= esc($tag) ?></span>
                                <?php endforeach ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </a>
        <?php endforeach ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav class="mt-4 d-flex justify-content-center">
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?q=<?= urlencode($q) ?>&page=<?= $page - 1 ?>">‹ Anterior</a>
                    </li>
                <?php endif ?>
                <?php for ($p = max(1, $page - 2); $p <= min($totalPages, $page + 2); $p++): ?>
                    <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?q=<?= urlencode($q) ?>&page=<?= $p ?>"><?= $p ?></a>
                    </li>
                <?php endfor ?>
                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?q=<?= urlencode($q) ?>&page=<?= $page + 1 ?>">Próxima ›</a>
                    </li>
                <?php endif ?>
            </ul>
        </nav>
    <?php endif ?>

<?php else: ?>
    <div class="empty-state">
        <i class="fas fa-images"></i>
        <p class="mt-2 text-muted">Digite uma palavra acima para buscar em todos os ensaios.</p>
        <div class="mt-3 d-flex gap-2 justify-content-center flex-wrap">
            <?php foreach (['pessoa', 'sorrindo', 'vestido', 'jeans', 'óculos', 'piano', 'jardim', 'noite'] as $sugestao): ?>
                <a href="?q=<?= urlencode($sugestao) ?>" class="btn btn-sm btn-outline-secondary" style="border-radius:20px;font-size:0.8rem;"><?= $sugestao ?></a>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>

<?= $this->endSection() ?>
