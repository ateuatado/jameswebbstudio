<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca de Fotos — James Webb Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --gold: #c5a059;
            --gold-dim: rgba(197,160,89,0.15);
            --bg: #0a0a0a;
            --card: #141414;
            --border: rgba(255,255,255,0.07);
        }
        * { box-sizing: border-box; }
        body {
            background: var(--bg);
            color: #f0f0f0;
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
        }

        /* Header */
        .portal-header {
            background: #000;
            border-bottom: 1px solid rgba(197,160,89,0.2);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .portal-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }
        .portal-brand-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.05em;
        }
        .portal-brand-sub {
            font-size: 0.7rem;
            color: var(--gold);
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }
        .portal-user-badge {
            background: var(--gold-dim);
            border: 1px solid rgba(197,160,89,0.25);
            color: var(--gold);
            font-size: 0.75rem;
            padding: 4px 12px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Search Area */
        .search-section {
            background: linear-gradient(180deg, #0f0e0a 0%, #0a0a0a 100%);
            padding: 2.5rem 2rem 2rem;
            border-bottom: 1px solid var(--border);
        }
        .search-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.25rem;
        }
        .search-subtitle {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 1.5rem;
        }
        .search-form {
            display: flex;
            gap: 0.75rem;
            max-width: 760px;
        }
        .search-field {
            flex: 1;
            background: #111 !important;
            border: 1px solid rgba(197,160,89,0.35) !important;
            color: #fff !important;
            padding: 0.8rem 1.1rem !important;
            border-radius: 10px !important;
            font-size: 1rem !important;
            font-family: 'Outfit', sans-serif !important;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .search-field:focus {
            border-color: var(--gold) !important;
            box-shadow: 0 0 0 3px rgba(197,160,89,0.12) !important;
            outline: none !important;
        }
        .btn-search {
            background: var(--gold);
            color: #000;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            border: none;
            padding: 0.8rem 1.8rem;
            border-radius: 10px;
            white-space: nowrap;
            transition: background 0.2s, transform 0.2s;
        }
        .btn-search:hover { background: #d4b06a; transform: translateY(-1px); }

        /* Results */
        .results-section { padding: 2rem; }
        .results-meta {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
            gap: 1.25rem;
            align-items: start;
        }
        .photo-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            transition: border-color 0.3s, transform 0.3s, box-shadow 0.3s;
            cursor: zoom-in;
        }
        .photo-card:hover {
            border-color: var(--gold);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .photo-card img {
            width: 100%;
            height: auto;
            max-height: 260px;
            object-fit: contain;
            background: #000;
            display: block;
        }
        .photo-card-body { padding: 10px 12px 12px; }
        .project-pill {
            display: inline-block;
            background: var(--gold-dim);
            color: var(--gold);
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 2px 8px;
            border-radius: 20px;
            border: 1px solid rgba(197,160,89,0.2);
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .photo-filename { font-size: 0.7rem; color: #555; margin-top: 4px; }
        .tags-wrap { margin-top: 6px; display: flex; flex-wrap: wrap; gap: 3px; }
        .tag { background: #1e1e1e; color: #888; font-size: 0.58rem; padding: 2px 6px; border-radius: 8px; border: 1px solid #2a2a2a; }
        .tag.hl { background: rgba(197,160,89,0.18); color: var(--gold); border-color: rgba(197,160,89,0.3); }

        /* Empty state */
        .empty-state { text-align: center; padding: 5rem 2rem; color: #444; }
        .empty-state i { font-size: 3rem; color: #2a2a2a; }
        .suggestions { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; margin-top: 1.5rem; }
        .suggestion-btn {
            background: #141414;
            color: #888;
            border: 1px solid #2a2a2a;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 0.8rem;
            text-decoration: none;
            transition: all 0.2s;
        }
        .suggestion-btn:hover { border-color: var(--gold); color: var(--gold); }

        /* Pagination */
        .pagination .page-link { background: var(--card); border-color: var(--border); color: #888; }
        .pagination .active .page-link { background: var(--gold); border-color: var(--gold); color: #000; }
        .pagination .page-link:hover { background: #1e1e1e; color: var(--gold); }

        /* Lightbox */
        .lightbox-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.92);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 1rem;
        }
        .lightbox-overlay.active { display: flex; }
        .lightbox-img { max-width: 90vw; max-height: 80vh; object-fit: contain; border-radius: 8px; }
        .lightbox-meta { color: #aaa; font-size: 0.85rem; text-align: center; }
        .lightbox-close {
            position: absolute; top: 1.5rem; right: 1.5rem;
            color: #aaa; font-size: 1.5rem; cursor: pointer;
            background: none; border: none; transition: color 0.2s;
        }
        .lightbox-close:hover { color: var(--gold); }
    </style>
</head>
<body>

<!-- Header -->
<div class="portal-header">
    <a href="<?= site_url('/') ?>" class="portal-brand" target="_blank">
        <div>
            <div class="portal-brand-text">JAMES WEBB STUDIO</div>
            <div class="portal-brand-sub">Portal de Busca de Fotos</div>
        </div>
    </a>
    <div class="d-flex align-items-center gap-3">
        <div class="portal-user-badge">
            <i class="fas fa-user-circle"></i>
            <?= esc(auth()->user()->username ?? auth()->user()->email) ?>
        </div>
        <a href="<?= site_url('logout') ?>" class="btn btn-sm btn-outline-secondary" style="font-size:0.75rem;">
            Sair
        </a>
    </div>
</div>

<!-- Search Section -->
<div class="search-section">
    <div class="search-title">🔍 Busca de Fotos por IA</div>
    <div class="search-subtitle">Pesquise por elementos, objetos ou características em todos os ensaios do estúdio</div>
    <form method="get" action="<?= site_url('portal/busca') ?>" class="search-form">
        <input
            type="text"
            name="q"
            class="search-field"
            placeholder="Ex: vestido azul, óculos, sorrindo, piano, jardim..."
            value="<?= esc($q) ?>"
            autofocus
        >
        <button type="submit" class="btn-search">
            <i class="fas fa-search me-2"></i>Buscar
        </button>
    </form>
</div>

<!-- Results -->
<div class="results-section">

    <?php if (!empty($q)): ?>
        <div class="results-meta">
            <span>
                <?php if ($total > 0): ?>
                    <strong style="color:#fff;"><?= $total ?></strong> foto<?= $total !== 1 ? 's' : '' ?> encontrada<?= $total !== 1 ? 's' : '' ?> para
                    <strong style="color:var(--gold);">"<?= esc($q) ?>"</strong>
                <?php else: ?>
                    Nenhuma foto encontrada para <strong style="color:var(--gold);">"<?= esc($q) ?>"</strong>
                <?php endif ?>
            </span>
            <?php if ($totalPages > 1): ?>
                <span>Página <?= $page ?> de <?= $totalPages ?></span>
            <?php endif ?>
        </div>
    <?php endif ?>

    <?php if (!empty($results)): ?>
        <div class="results-grid">
            <?php foreach ($results as $photo): ?>
                <div class="photo-card"
                     onclick="openLightbox('<?= esc($photo->presigned_url) ?>', '<?= esc($photo->original_filename) ?>', '<?= esc($photo->project_name) ?>')"
                >
                    <img src="<?= esc($photo->presigned_url) ?>" alt="<?= esc($photo->original_filename) ?>" loading="lazy">
                    <div class="photo-card-body">
                        <span class="project-pill" title="<?= esc($photo->project_name) ?>">
                            📁 <?= esc($photo->project_name) ?>
                        </span>
                        <div class="photo-filename"><?= esc($photo->original_filename) ?></div>
                        <?php if (!empty($photo->ai_tags)): ?>
                            <div class="tags-wrap">
                                <?php
                                $tags   = array_slice(explode(',', $photo->ai_tags), 0, 8);
                                $qLower = strtolower(trim($q));
                                foreach ($tags as $tag):
                                    $tag = trim($tag);
                                    if (!$tag) continue;
                                    $hl = str_contains(strtolower($tag), $qLower);
                                ?>
                                    <span class="tag <?= $hl ? 'hl' : '' ?>"><?= esc($tag) ?></span>
                                <?php endforeach ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <nav class="mt-4 d-flex justify-content-center">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?q=<?= urlencode($q) ?>&page=<?= $page-1 ?>">‹</a></li>
                    <?php endif ?>
                    <?php for ($p = max(1,$page-2); $p <= min($totalPages,$page+2); $p++): ?>
                        <li class="page-item <?= $p===$page?'active':'' ?>">
                            <a class="page-link" href="?q=<?= urlencode($q) ?>&page=<?= $p ?>"><?= $p ?></a>
                        </li>
                    <?php endfor ?>
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item"><a class="page-link" href="?q=<?= urlencode($q) ?>&page=<?= $page+1 ?>">›</a></li>
                    <?php endif ?>
                </ul>
            </nav>
        <?php endif ?>

    <?php elseif (!empty($q)): ?>
        <div class="empty-state">
            <i class="fas fa-image-slash"></i>
            <p class="mt-3">Nenhuma foto com essa tag foi encontrada.</p>
            <p style="font-size:0.8rem;">As tags são geradas automaticamente pela IA no momento do upload das fotos.</p>
        </div>

    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-images"></i>
            <p class="mt-3">Digite uma palavra acima para pesquisar</p>
            <div class="suggestions">
                <?php foreach (['pessoa', 'sorrindo', 'vestido', 'jeans', 'óculos', 'exterior', 'jardim', 'noite', 'família', 'criança'] as $s): ?>
                    <a href="?q=<?= urlencode($s) ?>" class="suggestion-btn"><?= $s ?></a>
                <?php endforeach ?>
            </div>
        </div>
    <?php endif ?>

</div>

<!-- Lightbox -->
<div class="lightbox-overlay" id="lightbox" onclick="closeLightbox()">
    <button class="lightbox-close" onclick="closeLightbox()">✕</button>
    <img class="lightbox-img" id="lightboxImg" src="" alt="">
    <div class="lightbox-meta" id="lightboxMeta"></div>
</div>

<script>
function openLightbox(url, filename, project) {
    document.getElementById('lightboxImg').src = url;
    document.getElementById('lightboxMeta').innerHTML = `<strong style="color:#fff">${filename}</strong> &nbsp;·&nbsp; ${project}`;
    document.getElementById('lightbox').classList.add('active');
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
</script>
</body>
</html>
