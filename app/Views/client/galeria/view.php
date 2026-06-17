<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --mst-gold: #c5a059;
        --mst-gold-dark: #a37f3d;
        --mst-bg-dark: #0a0a0a;
        --mst-card-bg: #141414;
        --mst-border: rgba(197, 160, 89, 0.2);
    }

    body {
        background-color: var(--mst-bg-dark) !important;
        color: #f5f5f5 !important;
    }

    .photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        align-items: start;
    }

    .photo-item {
        position: relative;
        background: var(--mst-card-bg);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .photo-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5), 0 0 0 1px var(--mst-gold);
    }

    .photo-item.selected {
        box-shadow: 0 0 0 3px var(--mst-gold), 0 10px 20px rgba(0, 0, 0, 0.6);
        border-color: var(--mst-gold);
    }

    .photo-wrapper {
        position: relative;
        width: 100%;
        height: auto;
        overflow: hidden;
        background: #0d0d0d;
        display: block;
    }

    .photo-protection-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 5;
        background: transparent;
        user-select: none;
        -webkit-user-drag: none;
    }

    .photo-wrapper img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.5s ease;
    }

    .photo-item:hover .photo-wrapper img {
        transform: scale(1.03);
    }

    /* Classificação de estrelas sempre visível */
    .rating-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        opacity: 0.9;
        transition: all 0.3s ease;
    }

    .star-icon {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.2);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .star-icon:hover,
    .star-icon.active {
        color: var(--mst-gold);
        transform: scale(1.2);
        text-shadow: 0 0 10px rgba(197, 160, 89, 0.5);
    }

    /* Botão de Amei/Coração Premium */
    .btn-love {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: rgba(255, 255, 255, 0.5);
        border-radius: 8px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-love:hover {
        color: #ff4757;
        background: rgba(255, 71, 87, 0.1);
        border-color: rgba(255, 71, 87, 0.3);
        transform: translateY(-1px);
    }

    .btn-love.loved {
        background: #ff4757;
        border-color: #ff4757;
        color: #fff !important;
        box-shadow: 0 0 12px rgba(255, 71, 87, 0.45);
        animation: heartBeat 0.4s ease-out;
    }

    @keyframes heartBeat {
        0% { transform: scale(1); }
        35% { transform: scale(1.2); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); }
    }

    /* Botão de Selecionar Premium */
    .btn-select {
        background: rgba(197, 160, 89, 0.06);
        border: 1px solid rgba(197, 160, 89, 0.3);
        color: var(--mst-gold);
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-select:hover {
        background: var(--mst-gold);
        color: #000;
        border-color: var(--mst-gold);
        box-shadow: 0 4px 12px rgba(197, 160, 89, 0.3);
        transform: translateY(-1px);
    }

    .btn-select.selected {
        background: #2ed573;
        border-color: #2ed573;
        color: #000;
        font-weight: 700;
        box-shadow: 0 0 12px rgba(46, 213, 115, 0.45);
    }

    /* Info do rodapé do card */
    .photo-footer {
        padding: 10px 14px;
        background: rgba(0,0,0,0.3);
        border-top: 1px solid rgba(255,255,255,0.03);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
    }

    .photo-name {
        color: #888;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 140px;
    }

    .photo-index {
        background: rgba(197, 160, 89, 0.15);
        color: var(--mst-gold);
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.7rem;
    }

    /* Barra flutuante refinada */
    .floating-bar {
        position: fixed;
        bottom: 0;
        left: 0; left: 0;
        right: 0;
        background: rgba(10, 10, 10, 0.92);
        backdrop-filter: blur(20px);
        border-top: 1px solid var(--mst-gold);
        padding: 1.25rem 0;
        z-index: 1000;
        transform: translateY(100%);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.8);
    }

    .floating-bar.visible {
        transform: translateY(0);
    }

    .btn-terroso {
        background: var(--mst-gold);
        color: #000;
        font-weight: 600;
        border: none;
        padding: 10px 24px;
        border-radius: 30px;
        transition: all 0.3s ease;
    }

    .btn-terroso:hover {
        background: var(--mst-gold-dark);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(197, 160, 89, 0.4);
        color: #000;
    }

    /* Badge viva Sincronizando */
    .sync-status {
        position: fixed;
        top: 90px;
        right: 24px;
        background: rgba(20, 20, 20, 0.85);
        backdrop-filter: blur(10px);
        border: 1px solid var(--mst-border);
        border-radius: 30px;
        padding: 6px 14px;
        font-size: 0.75rem;
        color: #aaa;
        display: flex;
        align-items: center;
        gap: 8px;
        z-index: 1001;
        box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        transition: all 0.3s ease;
    }

    .sync-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #2ed573;
        animation: pulse 1.5s infinite;
    }

    .sync-dot.syncing {
        background: var(--mst-gold);
    }

    @keyframes pulse {
        0% { transform: scale(0.9); opacity: 0.6; }
        50% { transform: scale(1.2); opacity: 1; }
        100% { transform: scale(0.9); opacity: 0.6; }
    }

    /* Skeleton e Empty State */
    .photo-skeleton {
        width: 100%;
        height: 280px;
        background: linear-gradient(90deg, #141414 25%, #222 50%, #141414 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 12px 12px 0 0;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .empty-state {
        text-align: center;
        padding: 6rem 2rem;
        color: #666;
        border: 2px dashed rgba(255,255,255,0.05);
        border-radius: 20px;
        background: rgba(255,255,255,0.01);
    }

    .empty-state .icon {
        font-size: 4.5rem;
        margin-bottom: 1.5rem;
        color: var(--mst-gold);
        opacity: 0.6;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }

    /* Efeito de fade-in para novas fotos */
    .fade-in-item {
        animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Status de Sincronização em Tempo Real -->
<div class="sync-status" id="syncStatus">
    <div class="sync-dot" id="syncDot"></div>
    <span id="syncText">Studio Conectado</span>
</div>

<div class="container-fluid px-4" style="margin-top: 110px; padding-bottom: 150px;">

    <!-- Cabeçalho Principal -->
    <div class="text-center mb-5">
        <h1 class="text-gold brand-font text-uppercase mb-2" style="font-size: 2.2rem; letter-spacing: 0.15em; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
            <?= esc($project->name ?? 'Minha Galeria') ?>
        </h1>
        <p class="text-muted mb-2">
            Cliente: <strong class="text-white"><?= esc(auth()->user()->username ?? auth()->user()->email) ?></strong> &mdash; 
            Pacote: <strong class="text-white"><?= esc($package->name) ?></strong>
        </p>
        <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
            <span class="badge" style="background: rgba(197, 160, 89, 0.15); color: var(--mst-gold); border: 1px solid var(--mst-border); padding: 6px 12px;">
                Inclui: <strong><?= esc($package->included_photos) ?> fotos</strong>
            </span>
            <span class="badge" style="background: rgba(255,255,255,0.05); color: #ccc; border: 1px solid rgba(255,255,255,0.1); padding: 6px 12px;">
                Foto Extra: <strong>R$ <?= number_format($package->extra_photo_price, 2, ',', '.') ?></strong>
            </span>
            <span class="badge bg-<?= $project->status === 'completed' ? 'success' : ($project->status === 'selecting' ? 'warning text-dark' : 'secondary') ?>" id="projectStatusBadge" style="padding: 6px 12px;">
                <?= $project->status === 'completed' ? 'Seleção Finalizada' : ($project->status === 'selecting' ? 'Em Seleção' : 'Aguardando Fotos') ?>
            </span>
        </div>
    </div>

    <!-- Grade de Fotos Dinâmica -->
    <div class="photo-grid" id="photoGrid">
        <?php if (!empty($photos)): ?>
            <?php foreach ($photos as $i => $photo): ?>
                <div class="photo-item <?= $photo->status === 'selected' ? 'selected' : '' ?>" id="photo-card-<?= $photo->id ?>" data-id="<?= $photo->id ?>">
                    
                    <!-- Imagem Proxy em Aspecto Total -->
                    <div class="photo-wrapper" onclick="toggleSelect(<?= $project->id ?>, <?= $photo->id ?>, this.closest('.photo-item').querySelector('.select-btn'), event)">
                        <div class="photo-protection-overlay"></div>
                        <?php if (!empty($photo->presigned_url)): ?>
                            <img src="<?= esc($photo->presigned_url) ?>" alt="Foto <?= $i + 1 ?>" class="gallery-img" loading="lazy">
                        <?php else: ?>
                            <div class="photo-skeleton"></div>
                        <?php endif; ?>
                    </div>

                    <!-- Barra de Avaliação (Estrelas) - Sempre Visível -->
                    <div class="rating-container py-2 text-center border-bottom border-secondary" style="background: rgba(0,0,0,0.35);">
                        <div class="rating-bar d-inline-flex gap-2">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <i class="fas fa-star star-icon <?= $s <= $photo->rating ? 'active' : '' ?>" 
                                   data-value="<?= $s ?>" 
                                   onclick="setRating(<?= $project->id ?>, <?= $photo->id ?>, <?= $s ?>, this)"></i>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <!-- Rodapé do Card com Nome e Ações Explícitas -->
                    <div class="photo-footer d-flex flex-column gap-2 p-3" style="background: #121212;">
                        <div class="d-flex justify-content-between align-items-center mb-1 w-100">
                            <span class="photo-name text-muted text-truncate" style="max-width: 170px;" title="<?= esc($photo->original_filename) ?>"><?= esc($photo->original_filename) ?></span>
                            <span class="photo-index">#<?= $i + 1 ?></span>
                        </div>
                        
                        <div class="d-flex gap-2 w-100 mt-1">
                            <!-- Botão Amei (Coração) -->
                            <button class="btn btn-love w-25 <?= $photo->is_loved == 1 ? 'loved' : '' ?>" 
                                    onclick="toggleLove(<?= $project->id ?>, <?= $photo->id ?>, this, event)" 
                                    title="Amei esta foto">
                                <i class="fas fa-heart"></i>
                            </button>
                            
                            <!-- Botão Selecionar -->
                            <button class="btn btn-select w-75 select-btn <?= $photo->status === 'selected' ? 'selected' : '' ?>" 
                                    onclick="toggleSelect(<?= $project->id ?>, <?= $photo->id ?>, this, event)">
                                <i class="fas <?= $photo->status === 'selected' ? 'fa-check-circle' : 'fa-plus-circle' ?> me-1"></i>
                                <span class="btn-text"><?= $photo->status === 'selected' ? 'Selecionada' : 'Selecionar' ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Mostrar em estado vazio se não houver fotos ainda -->
            <div class="w-100 grid-full-width" id="emptyStatePlaceholder">
                <div class="empty-state">
                    <div class="icon"><i class="fas fa-camera-retro"></i></div>
                    <h4 class="text-white-50 mb-2">Preparando sua sessão no Studio...</h4>
                    <p class="text-muted small">
                        O fotógrafo está preparando as fotos do seu ensaio.<br>
                        Assim que as fotos forem tiradas, elas surgirão aqui **automaticamente** em tempo real!
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>

<!-- Barra Flutuante Dinâmica de Fechamento -->
<div class="floating-bar <?= !empty($photos) ? 'visible' : '' ?>" id="floatingBar">
    <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="mb-0 text-white brand-font">
                Selecionadas: <span id="selectedCount" class="text-gold" style="font-weight: 700; font-size: 1.4rem;">0</span>
                <span class="text-muted fw-normal" style="font-size:0.85rem;">/ <?= esc($package->included_photos) ?> inclusas</span>
            </h5>
            <small class="text-muted" id="extraInfo">Carregando informações...</small>
        </div>
        <div class="d-flex gap-3">
            <span id="saveIndicator" class="text-success align-self-center me-2 d-none" style="font-size: 0.9rem;">
                <i class="fas fa-check-circle me-1"></i> Sincronizado
            </span>
            <?php if ($project->status !== 'completed'): ?>
                <a href="<?= site_url('client/galeria/' . $project->id . '/checkout') ?>"
                   class="btn btn-terroso d-flex align-items-center gap-2" id="btnCheckout">
                    <span>Finalizar Seleção</span> <i class="fas fa-arrow-right"></i>
                </a>
            <?php else: ?>
                <button class="btn btn-secondary" disabled>Sessão Concluída</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const projectId      = <?= (int)$project->id ?>;
    const includedPhotos = <?= (int)$package->included_photos ?>;
    const extraPrice     = <?= (float)$package->extra_photo_price ?>;
    const isCompleted    = <?= $project->status === 'completed' ? 'true' : 'false' ?>;
    
    // Conjunto de IDs selecionados no client-side
    let selectedIds = new Set();
    
    // Armazena as keys/filenames existentes locais para evitar piscar imagens ao atualizar no poll
    let existingPhotoIds = new Set();

    // Inicializa os IDs que vieram marcados do PHP
    document.querySelectorAll('.photo-item.selected').forEach(el => {
        selectedIds.add(parseInt(el.dataset.id));
    });
    document.querySelectorAll('.photo-item').forEach(el => {
        existingPhotoIds.add(parseInt(el.dataset.id));
    });

    const fmtBRL = val => 'R$ ' + val.toFixed(2).replace('.', ',');

    // Atualiza a Barra Flutuante com cálculos de extras
    function updateFloatingBar() {
        const count = selectedIds.size;
        const extra = Math.max(0, count - includedPhotos);

        const countEl = document.getElementById('selectedCount');
        const infoEl  = document.getElementById('extraInfo');
        const bar     = document.getElementById('floatingBar');

        if (countEl) countEl.textContent = count;

        if (infoEl) {
            if (extra > 0) {
                const totalCost = extra * extraPrice;
                infoEl.innerHTML = `<span class="text-warning fw-600">${extra} foto${extra > 1 ? 's' : ''} extra${extra > 1 ? 's' : ''} &mdash; +${fmtBRL(totalCost)}</span>`;
            } else {
                infoEl.innerHTML = `<span class="text-success">Sem custos extras incluídos no pacote.</span>`;
            }
        }

        if (bar) {
            bar.classList.toggle('visible', count > 0 || document.querySelectorAll('.photo-item').length > 0);
        }
    }

    // Mostra indicador rápido "Sincronizado" ao salvar ações via AJAX
    function showSavedIndicator() {
        const ind = document.getElementById('saveIndicator');
        if (ind) {
            ind.classList.remove('d-none');
            setTimeout(() => ind.classList.add('d-none'), 2000);
        }
    }

    // 1. AÇÃO INSTANTÂNEA: Curtir/Love
    function toggleLove(projId, photoId, btn, event) {
        if (event) event.stopPropagation();
        if (isCompleted) return;

        btn.disabled = true;
        fetch(`<?= site_url('client/galeria') ?>/${projId}/photo/${photoId}/love`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                btn.classList.toggle('loved', data.is_loved == 1);
                showSavedIndicator();
                updateProjectStatusBadge('selecting');
            }
        })
        .catch(err => console.error('Erro ao curtir foto:', err))
        .finally(() => {
            btn.disabled = false;
        });
    }

    // 2. AÇÃO INSTANTÂNEA: Selecionar
    function toggleSelect(projId, photoId, badge, event) {
        if (event) event.stopPropagation();
        if (isCompleted) return;

        const card = document.getElementById(`photo-card-${photoId}`);
        if (!card) return;

        // AJAX POST instantâneo
        fetch(`<?= site_url('client/galeria') ?>/${projId}/photo/${photoId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const icon = badge.querySelector('i');
                const btnText = badge.querySelector('.btn-text');
                if (data.status === 'selected') {
                    card.classList.add('selected');
                    badge.classList.add('selected');
                    selectedIds.add(photoId);
                    if (icon) {
                        icon.className = 'fas fa-check-circle me-1';
                    }
                    if (btnText) {
                        btnText.textContent = 'Selecionada';
                    }
                } else {
                    card.classList.remove('selected');
                    badge.classList.remove('selected');
                    selectedIds.delete(photoId);
                    if (icon) {
                        icon.className = 'fas fa-plus-circle me-1';
                    }
                    if (btnText) {
                        btnText.textContent = 'Selecionar';
                    }
                }
                updateFloatingBar();
                showSavedIndicator();
                updateProjectStatusBadge('selecting');
            }
        })
        .catch(err => console.error('Erro ao selecionar foto:', err));
    }

    // 3. AÇÃO INSTANTÂNEA: Dar Nota/Estrela
    function setRating(projId, photoId, stars, starElement) {
        if (isCompleted) return;

        fetch(`<?= site_url('client/galeria') ?>/${projId}/photo/${photoId}/rate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ rating: stars })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const parent = starElement.parentNode;
                const starIcons = parent.querySelectorAll('.star-icon');
                starIcons.forEach(s => {
                    const val = parseInt(s.dataset.value);
                    s.classList.toggle('active', val <= data.rating);
                });
                showSavedIndicator();
                updateProjectStatusBadge('selecting');
            }
        })
        .catch(err => console.error('Erro ao classificar foto:', err));
    }

    // Atualiza o badge do status do projeto caso mude de "open" para "selecting"
    function updateProjectStatusBadge(status) {
        const badge = document.getElementById('projectStatusBadge');
        if (badge) {
            if (status === 'selecting') {
                badge.className = 'badge bg-warning text-dark';
                badge.textContent = 'Em Seleção';
            } else if (status === 'completed') {
                badge.className = 'badge bg-success';
                badge.textContent = 'Seleção Finalizada';
            }
        }
    }

    // 4. POLLING EM TEMPO REAL: Atualiza a lista de fotos e adiciona novas de forma viva
    function startRealTimeSync() {
        if (isCompleted) return;

        const syncDot = document.getElementById('syncDot');
        const syncText = document.getElementById('syncText');

        setInterval(() => {
            // Sinaliza atividade de sync piscando em dourado
            if (syncDot) syncDot.classList.add('syncing');
            if (syncText) syncText.textContent = 'Sincronizando...';

            fetch(`<?= site_url('client/galeria/' . $project->id . '/poll') ?>`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (syncText) syncText.textContent = 'Studio Conectado';
                    updateProjectStatusBadge(data.status);

                    const grid = document.getElementById('photoGrid');
                    const emptyPlaceholder = document.getElementById('emptyStatePlaceholder');

                    if (data.photos.length > 0) {
                        if (emptyPlaceholder) {
                            emptyPlaceholder.remove();
                        }
                    }

                    // Loop nas fotos retornadas pelo S3 Sync
                    data.photos.forEach((photo, index) => {
                        const id = parseInt(photo.id);

                        // Se a foto é nova, criamos o elemento dinamicamente e inserimos na grade
                        if (!existingPhotoIds.has(id)) {
                            existingPhotoIds.add(id);

                            const card = document.createElement('div');
                            card.className = `photo-item ${photo.status === 'selected' ? 'selected' : ''}`;
                            card.id = `photo-card-${id}`;
                            card.dataset.id = id;

                            // Monta estrelas
                            let starsHtml = '';
                            for (let s = 1; s <= 5; s++) {
                                starsHtml += `<i class="fas fa-star star-icon ${s <= (photo.rating ?? 0) ? 'active' : ''}" 
                                                 data-value="${s}" 
                                                 onclick="setRating(${projectId}, ${id}, ${s}, this)"></i>`;
                            }

                            card.innerHTML = `
                                <div class="photo-wrapper" onclick="toggleSelect(${projectId}, ${id}, this.closest('.photo-item').querySelector('.select-btn'), event)">
                                    <div class="photo-protection-overlay"></div>
                                    <img src="${photo.presigned_url}" alt="Foto ${index + 1}" class="gallery-img" loading="lazy">
                                </div>
                                <div class="rating-container py-2 text-center border-bottom border-secondary" style="background: rgba(0,0,0,0.35);">
                                    <div class="rating-bar d-inline-flex gap-2">
                                        ${starsHtml}
                                    </div>
                                </div>
                                <div class="photo-footer d-flex flex-column gap-2 p-3" style="background: #121212;">
                                    <div class="d-flex justify-content-between align-items-center mb-1 w-100">
                                        <span class="photo-name text-muted text-truncate" style="max-width: 170px;" title="${photo.original_filename}">${photo.original_filename}</span>
                                        <span class="photo-index">#${index + 1}</span>
                                    </div>
                                    <div class="d-flex gap-2 w-100 mt-1">
                                        <button class="btn btn-love w-25 ${photo.is_loved == 1 ? 'loved' : ''}" 
                                                onclick="toggleLove(${projectId}, ${id}, this, event)" 
                                                title="Amei esta foto">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                        <button class="btn btn-select w-75 select-btn ${photo.status === 'selected' ? 'selected' : ''}" 
                                                onclick="toggleSelect(${projectId}, ${id}, this, event)">
                                            <i class="fas ${photo.status === 'selected' ? 'fa-check-circle' : 'fa-plus-circle'} me-1"></i>
                                            <span class="btn-text">${photo.status === 'selected' ? 'Selecionada' : 'Selecionar'}</span>
                                        </button>
                                    </div>
                                </div>
                            `;

                            grid.appendChild(card);
                            
                            // Se já estiver marcada como selecionada na sincronização remota, atualiza o set local
                            if (photo.status === 'selected') {
                                selectedIds.add(id);
                            }
                        } else {
                            // Se a foto já existe, apenas atualizamos estados silenciosamente se alterados no banco por outro meio
                            const existingCard = document.getElementById(`photo-card-${id}`);
                            if (existingCard) {
                                // Atualiza o Love
                                const loveBtn = existingCard.querySelector('.btn-love');
                                if (loveBtn) {
                                    loveBtn.classList.toggle('loved', photo.is_loved == 1);
                                }

                                // Atualiza as Estrelas
                                const stars = existingCard.querySelectorAll('.star-icon');
                                stars.forEach(s => {
                                    const val = parseInt(s.dataset.value);
                                    s.classList.toggle('active', val <= (photo.rating ?? 0));
                                });

                                // Sincroniza o status de Seleção local caso tenha mudado remotamente
                                const selectBtn = existingCard.querySelector('.select-btn');
                                const selectIcon = selectBtn ? selectBtn.querySelector('i') : null;
                                const selectSpan = selectBtn ? selectBtn.querySelector('.btn-text') : null;
                                if (photo.status === 'selected') {
                                    existingCard.classList.add('selected');
                                    selectedIds.add(id);
                                    if (selectBtn) selectBtn.classList.add('selected');
                                    if (selectIcon) selectIcon.className = 'fas fa-check-circle me-1';
                                    if (selectSpan) selectSpan.textContent = 'Selecionada';
                                } else {
                                    existingCard.classList.remove('selected');
                                    selectedIds.delete(id);
                                    if (selectBtn) selectBtn.classList.remove('selected');
                                    if (selectIcon) selectIcon.className = 'fas fa-plus-circle me-1';
                                    if (selectSpan) selectSpan.textContent = 'Selecionar';
                                }
                            }
                        }
                    });

                    updateFloatingBar();
                }
            })
            .catch(err => console.error('Erro de conexão ao sincronizar com o S3:', err))
            .finally(() => {
                setTimeout(() => {
                    if (syncDot) syncDot.classList.remove('syncing');
                }, 1000);
            });
        }, 3000);
    }

    // Inicializa a UI ao carregar a página
    updateFloatingBar();
    startRealTimeSync();

    // Bloqueia clique com o botão direito nas imagens e no wrapper da galeria
    document.addEventListener('contextmenu', function(e) {
        if (e.target.classList.contains('gallery-img') || e.target.classList.contains('photo-protection-overlay') || e.target.closest('.photo-wrapper')) {
            e.preventDefault();
        }
    });

    // Bloqueia arrastar e soltar (drag and drop) de imagens
    document.addEventListener('dragstart', function(e) {
        if (e.target.classList.contains('gallery-img') || e.target.classList.contains('photo-protection-overlay') || e.target.closest('.photo-wrapper')) {
            e.preventDefault();
        }
    });

    // Bloqueia atalhos comuns de teclado para salvar e inspecionar
    document.addEventListener('keydown', function(e) {
        // Bloqueia F12 (DevTools)
        if (e.key === 'F12') {
            e.preventDefault();
        }
        // Bloqueia Ctrl+S ou Cmd+S (Salvar página)
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
        }
        // Bloqueia Ctrl+U ou Cmd+U (Ver código-fonte)
        if ((e.ctrlKey || e.metaKey) && e.key === 'u') {
            e.preventDefault();
        }
        // Bloqueia Ctrl+Shift+I ou Cmd+Opt+I (Inspecionar)
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && (e.key === 'I' || e.key === 'i')) {
            e.preventDefault();
        }
    });
</script>
<?= $this->endSection() ?>
