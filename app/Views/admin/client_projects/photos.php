<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --mst-gold: #c5a059;
        --mst-gold-dark: #a37f3d;
        --mst-bg-dark: #0f0f0f;
        --mst-card-bg: #181818;
        --mst-border: rgba(197, 160, 89, 0.25);
    }

    .brand-font {
        font-family: 'Outfit', 'Inter', sans-serif;
    }

    .text-gold {
        color: var(--mst-gold);
    }

    .card-stats {
        background: var(--mst-card-bg);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .card-stats:hover {
        border-color: var(--mst-gold);
        transform: translateY(-2px);
    }

    .photo-grid-admin {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.25rem;
    }

    .photo-item-admin {
        position: relative;
        background: var(--mst-card-bg);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .photo-item-admin.selected {
        border-color: var(--mst-gold);
        box-shadow: 0 0 15px rgba(197, 160, 89, 0.25);
    }

    .photo-wrapper-admin {
        position: relative;
        width: 100%;
        height: 180px;
        background: #000;
        overflow: hidden;
    }

    .photo-wrapper-admin img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.85;
        transition: opacity 0.3s ease;
    }

    .photo-item-admin.selected img {
        opacity: 1;
    }

    /* Badges de Interação do Cliente */
    .interaction-badges {
        position: absolute;
        top: 8px;
        left: 8px;
        right: 8px;
        display: flex;
        justify-content: space-between;
        pointer-events: none;
        z-index: 10;
    }

    .badge-love {
        background: rgba(255, 71, 87, 0.9);
        color: white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        opacity: 0;
        transform: scale(0);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .badge-love.active {
        opacity: 1;
        transform: scale(1);
    }

    .badge-select {
        background: var(--mst-gold);
        color: #000;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .badge-select.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* Rodapé das estrelas */
    .stars-indicator {
        position: absolute;
        bottom: 8px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.75);
        padding: 2px 8px;
        border-radius: 20px;
        display: flex;
        gap: 2px;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stars-indicator.active {
        opacity: 1;
    }

    .stars-indicator i {
        font-size: 0.75rem;
        color: var(--mst-gold);
    }

    .photo-footer-admin {
        padding: 8px 12px;
        background: rgba(0,0,0,0.25);
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .filename-text {
        font-size: 0.75rem;
        color: #999;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Live Badge no cabeçalho */
    .live-indicator {
        background: rgba(220, 53, 69, 0.15);
        border: 1px solid rgba(220, 53, 69, 0.3);
        color: #dc3545;
        border-radius: 30px;
        padding: 4px 12px;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .live-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #dc3545;
        animation: blink 1.2s infinite;
    }

    @keyframes blink {
        0% { opacity: 0.4; }
        50% { opacity: 1; }
        100% { opacity: 0.4; }
    }
</style>

<!-- Cabeçalho de Acompanhamento -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="brand-font text-white mb-1">Acompanhamento de Seleção</h2>
        <h4 class="text-gold mb-0 fs-5"><?= esc($project->name) ?></h4>
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="live-indicator">
            <div class="live-dot"></div>
            <span>Monitorando Interações</span>
        </div>
        <a href="<?= site_url('admin/client-projects/' . $project->id . '/download-bat') ?>" class="btn btn-outline-gold" title="Baixar script (.bat) para conectar a câmera/tethering diretamente ao Disco S:">
            <i class="fas fa-file-download me-1"></i> Script Estúdio (.bat)
        </a>
        <a href="<?= site_url('admin/client-projects') ?>" class="btn btn-outline-secondary">Voltar</a>
    </div>
</div>

<!-- Grid de Cards de Estatísticas em Tempo Real -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-stats text-white h-100">
            <div class="card-body text-center p-3">
                <small class="text-muted text-uppercase d-block mb-1">Total de Fotos</small>
                <h3 class="brand-font mb-0 text-white" id="statTotalPhotos"><?= count($photos) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stats text-white h-100">
            <div class="card-body text-center p-3">
                <small class="text-muted text-uppercase d-block mb-1">Selecionadas pelo Cliente</small>
                <h3 class="brand-font mb-0 text-gold" id="statSelectedCount">0</h3>
                <small class="text-muted" id="statIncludedInfo">/ <?= esc($package->included_photos) ?> inclusas</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stats text-white h-100">
            <div class="card-body text-center p-3">
                <small class="text-muted text-uppercase d-block mb-1">Curtidas (Coração)</small>
                <h3 class="brand-font mb-0 text-danger" id="statLovedCount">0</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stats text-white h-100">
            <div class="card-body text-center p-3">
                <small class="text-muted text-uppercase d-block mb-1">Avaliadas com Estrelas</small>
                <h3 class="brand-font mb-0 text-info" id="statRatedCount">0</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filtro Lightroom e Exportação -->
<div class="card bg-dark text-white border-secondary mb-4">
    <div class="card-body">
        <h5 class="text-gold brand-font mb-2"><i class="fas fa-filter me-2"></i>Filtro Rápido para Lightroom / Exportação</h5>
        <p class="text-muted small mb-3">Conforme o cliente seleciona as fotos no estúdio, os nomes dos arquivos são listados abaixo separados por vírgula em tempo real. Basta copiar e colar na busca do Lightroom para filtrar as fotos escolhidas!</p>
        <div class="input-group">
            <textarea id="lightroomFilter" class="form-control bg-black text-white border-secondary" rows="2" readonly placeholder="Aguardando a seleção do cliente..."></textarea>
            <button class="btn btn-outline-gold" onclick="copyFilter()" type="button" title="Copiar lista">
                <i class="fas fa-copy"></i> Copiar
            </button>
        </div>
    </div>
</div>

<!-- Grid de Acompanhamento das Fotos -->
<div class="card bg-dark text-white border-secondary">
    <div class="card-body">
        <h4 class="mb-4 brand-font text-white">Galeria de Fotos do Projeto</h4>
        
        <!-- Barra de busca por IA -->
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text bg-black text-gold border-secondary">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="photoSearchInput" class="form-control bg-black text-white border-secondary" placeholder="Buscar por elemento ou tag da IA (ex: piano, cachorro, azul, blusa)..." oninput="filterPhotos()">
                <button class="btn btn-outline-gold" type="button" onclick="clearSearch()">Limpar</button>
            </div>
            <div class="form-text text-muted small mt-1">
                <i class="fas fa-brain me-1 text-gold"></i> Digite para buscar elementos identificados automaticamente nas fotos. A pesquisa busca no nome do arquivo, na descrição e nas etiquetas geradas pela IA.
            </div>
        </div>
        
        <div class="photo-grid-admin" id="adminPhotoGrid">
            <?php if (!empty($photos)): ?>
                <?php foreach ($photos as $i => $photo): ?>
                    <div class="photo-item-admin <?= $photo->status === 'selected' ? 'selected' : '' ?>" 
                         id="admin-photo-card-<?= $photo->id ?>" 
                         data-id="<?= $photo->id ?>" 
                         data-filename="<?= esc($photo->original_filename) ?>"
                         data-ai-description="<?= esc($photo->ai_description ?? '') ?>"
                         data-ai-tags="<?= esc($photo->ai_tags ?? '') ?>">
                        
                        <!-- Badges de Interação -->
                        <div class="interaction-badges">
                            <div class="badge-love <?= $photo->is_loved == 1 ? 'active' : '' ?>">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="badge-select <?= $photo->status === 'selected' ? 'active' : '' ?>">
                                Escolhida
                            </div>
                        </div>

                        <!-- Imagem -->
                        <div class="photo-wrapper-admin">
                            <img src="<?= esc($photo->presigned_url) ?>" alt="Foto" loading="lazy">

                            <!-- Estrelas -->
                            <div class="stars-indicator <?= $photo->rating > 0 ? 'active' : '' ?>">
                                <?php for ($s = 1; $s <= 5; $s++): ?>
                                    <i class="fas fa-star <?= $s <= $photo->rating ? 'active' : '' ?>" style="opacity: <?= $s <= $photo->rating ? '1' : '0.2' ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <!-- Rodapé -->
                        <div class="photo-footer-admin">
                            <span class="filename-text" title="<?= esc($photo->original_filename) ?>"><?= esc($photo->original_filename) ?></span>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-secondary" style="font-size:0.65rem;">#<?= $i + 1 ?></span>
                                <span class="badge text-uppercase text-gold" style="font-size:0.6rem; background:rgba(197, 160, 89, 0.15);" id="status-badge-<?= $photo->id ?>"><?= esc($photo->status) ?></span>
                            </div>
                            
                            <?php if (!empty($photo->ai_description) || !empty($photo->ai_tags)): ?>
                                <div class="ai-info-box mt-2 pt-2 border-top border-secondary" style="font-size: 0.65rem; color: #aaa;">
                                    <div class="d-flex align-items-center gap-1 mb-1 text-gold">
                                        <i class="fas fa-brain" style="font-size:0.6rem;"></i>
                                        <strong style="font-size:0.6rem;">IA</strong>
                                    </div>
                                    <div class="text-truncate" title="<?= esc($photo->ai_description) ?>"><?= esc($photo->ai_description) ?></div>
                                    <div class="mt-1 text-wrap text-muted" style="font-size: 0.6rem; line-height: 1.2;">
                                        <?php 
                                            $tags = explode(',', $photo->ai_tags ?? '');
                                            foreach ($tags as $tag) {
                                                $tag = trim($tag);
                                                if ($tag) {
                                                    echo '<span class="badge bg-dark text-light border border-secondary me-1 px-1 py-0" style="font-size:0.55rem;">' . esc($tag) . '</span>';
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="w-100 text-center py-5 text-muted" id="noPhotosPlaceholder">
                    <i class="fas fa-images fa-3x mb-3 text-secondary"></i>
                    <p>Nenhuma foto importada para este projeto ainda.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const projectId = <?= (int)$project->id ?>;
    let localPhotoIds = new Set();
    
    // Armazena os IDs locais iniciais
    document.querySelectorAll('.photo-item-admin').forEach(el => {
        localPhotoIds.add(parseInt(el.dataset.id));
    });

    // Copiar filtro Lightroom para o clipboard
    function copyFilter() {
        const textarea = document.getElementById('lightroomFilter');
        textarea.select();
        document.execCommand('copy');
        
        const btn = document.querySelector('button[onclick="copyFilter()"]');
        const origHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copiado!';
        btn.classList.remove('btn-outline-gold');
        btn.classList.add('btn-success');
        
        setTimeout(() => {
            btn.innerHTML = origHtml;
            btn.classList.add('btn-outline-gold');
            btn.classList.remove('btn-success');
        }, 2000);
    }

    // Helper para gerar o HTML do box de IA
    function getAiInfoHtml(photo) {
        if (!photo.ai_description && !photo.ai_tags) {
            return '';
        }
        const desc = photo.ai_description || '';
        const tagsStr = photo.ai_tags || '';
        const tags = tagsStr.split(',').map(t => t.trim()).filter(t => t.length > 0);
        let tagsHtml = '';
        tags.forEach(tag => {
            tagsHtml += `<span class="badge bg-dark text-light border border-secondary me-1 px-1 py-0" style="font-size:0.55rem;">${tag}</span>`;
        });
        return `
            <div class="ai-info-box mt-2 pt-2 border-top border-secondary" style="font-size: 0.65rem; color: #aaa;">
                <div class="d-flex align-items-center gap-1 mb-1 text-gold">
                    <i class="fas fa-brain" style="font-size:0.6rem;"></i>
                    <strong style="font-size:0.6rem;">IA</strong>
                </div>
                <div class="text-truncate" title="${desc}">${desc}</div>
                <div class="mt-1 text-wrap text-muted" style="font-size: 0.6rem; line-height: 1.2;">
                    ${tagsHtml}
                </div>
            </div>
        `;
    }

    // Filtragem cliente-side de fotos
    function filterPhotos() {
        const query = document.getElementById('photoSearchInput').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.photo-item-admin');
        
        cards.forEach(card => {
            const filename = (card.dataset.filename || '').toLowerCase();
            const desc = (card.dataset.aiDescription || '').toLowerCase();
            const tags = (card.dataset.aiTags || '').toLowerCase();
            
            if (!query || filename.includes(query) || desc.includes(query) || tags.includes(query)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function clearSearch() {
        document.getElementById('photoSearchInput').value = '';
        filterPhotos();
    }

    // Polling ativo a cada 3 segundos
    function startAdminPolling() {
        setInterval(() => {
            fetch(`<?= site_url('admin/client-projects/' . $project->id . '/poll') ?>`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // 1. Atualiza Estatísticas
                    document.getElementById('statTotalPhotos').textContent = data.stats.total;
                    document.getElementById('statSelectedCount').textContent = data.stats.selected;
                    document.getElementById('statLovedCount').textContent = data.stats.loved;
                    document.getElementById('statRatedCount').textContent = data.stats.rated;
 
                    const grid = document.getElementById('adminPhotoGrid');
                    const noPhotosPlaceholder = document.getElementById('noPhotosPlaceholder');
 
                    if (data.photos.length > 0 && noPhotosPlaceholder) {
                        noPhotosPlaceholder.remove();
                    }
 
                    let selectedFilenames = [];
 
                    // 2. Loop pelas fotos vindas da Sincronização S3
                    data.photos.forEach((photo, index) => {
                        const id = parseInt(photo.id);
 
                        // Coleta nomes das selecionadas para o filtro do Lightroom
                        if (photo.status === 'selected') {
                            selectedFilenames.push(photo.original_filename);
                        }
 
                        // Se é uma foto nova
                        if (!localPhotoIds.has(id)) {
                            localPhotoIds.add(id);
 
                            const card = document.createElement('div');
                            card.className = `photo-item-admin ${photo.status === 'selected' ? 'selected' : ''}`;
                            card.id = `admin-photo-card-${id}`;
                            card.dataset.id = id;
                            card.dataset.filename = photo.original_filename;
                            card.dataset.aiDescription = photo.ai_description || '';
                            card.dataset.aiTags = photo.ai_tags || '';
 
                            let starsHtml = '';
                            for (let s = 1; s <= 5; s++) {
                                starsHtml += `<i class="fas fa-star" style="opacity: ${s <= (photo.rating ?? 0) ? '1' : '0.2'}"></i>`;
                            }
 
                            card.innerHTML = `
                                <div class="interaction-badges">
                                    <div class="badge-love ${photo.is_loved == 1 ? 'active' : ''}">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="badge-select ${photo.status === 'selected' ? 'active' : ''}">
                                        Escolhida
                                    </div>
                                </div>
                                <div class="photo-wrapper-admin">
                                    <img src="${photo.presigned_url}" alt="Foto" loading="lazy">
                                    <div class="stars-indicator ${photo.rating > 0 ? 'active' : ''}">
                                        ${starsHtml}
                                    </div>
                                </div>
                                <div class="photo-footer-admin">
                                    <span class="filename-text" title="${photo.original_filename}">${photo.original_filename}</span>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-secondary" style="font-size:0.65rem;">#${index + 1}</span>
                                        <span class="badge text-uppercase text-gold" style="font-size:0.6rem; background:rgba(197, 160, 89, 0.15);" id="status-badge-${id}">${photo.status}</span>
                                    </div>
                                    ${getAiInfoHtml(photo)}
                                </div>
                            `;
 
                            grid.appendChild(card);
                        } else {
                            // Se já existe, atualizamos as badges e metadados dinamicamente
                            const existingCard = document.getElementById(`admin-photo-card-${id}`);
                            if (existingCard) {
                                // Atualiza atributos de busca
                                existingCard.dataset.aiDescription = photo.ai_description || '';
                                existingCard.dataset.aiTags = photo.ai_tags || '';
 
                                // Status
                                if (photo.status === 'selected') {
                                    existingCard.classList.add('selected');
                                    existingCard.querySelector('.badge-select').classList.add('active');
                                } else {
                                    existingCard.classList.remove('selected');
                                    existingCard.querySelector('.badge-select').classList.remove('active');
                                }
 
                                const statusBadge = document.getElementById(`status-badge-${id}`);
                                if (statusBadge) {
                                    statusBadge.textContent = photo.status;
                                }
 
                                // Love (Coração)
                                const loveBadge = existingCard.querySelector('.badge-love');
                                if (loveBadge) {
                                    loveBadge.classList.toggle('active', photo.is_loved == 1);
                                }
 
                                // Estrelas
                                const starsIndicator = existingCard.querySelector('.stars-indicator');
                                if (starsIndicator) {
                                    starsIndicator.classList.toggle('active', photo.rating > 0);
                                    let starsHtml = '';
                                    for (let s = 1; s <= 5; s++) {
                                        starsHtml += `<i class="fas fa-star" style="opacity: ${s <= (photo.rating ?? 0) ? '1' : '0.2'}"></i>`;
                                    }
                                    starsIndicator.innerHTML = starsHtml;
                                }
 
                                // Bloco de IA
                                let aiBox = existingCard.querySelector('.ai-info-box');
                                if (aiBox) {
                                    aiBox.remove();
                                }
                                if (photo.ai_description || photo.ai_tags) {
                                    const footer = existingCard.querySelector('.photo-footer-admin');
                                    if (footer) {
                                        footer.insertAdjacentHTML('beforeend', getAiInfoHtml(photo));
                                    }
                                }
                            }
                        }
                    });
 
                    // Atualiza a string do Lightroom
                    const filterArea = document.getElementById('lightroomFilter');
                    if (filterArea) {
                        filterArea.value = selectedFilenames.join(', ');
                    }
 
                    // Se uma busca estiver ativa, re-filtra as fotos atualizadas
                    filterPhotos();
                }
            })
            .catch(err => console.error('Erro no polling do admin:', err));
        }, 3000);
    }
 
    // Inicializa a string do Lightroom a partir das fotos selecionadas iniciais
    function initLightroomFilter() {
        let initialSelected = [];
        document.querySelectorAll('.photo-item-admin.selected').forEach(el => {
            initialSelected.push(el.dataset.filename);
        });
        document.getElementById('lightroomFilter').value = initialSelected.join(', ');
        
        // Inicializa estatísticas básicas iniciais no client-side
        const total = document.querySelectorAll('.photo-item-admin').length;
        const selected = document.querySelectorAll('.photo-item-admin.selected').length;
        const loved = document.querySelectorAll('.badge-love.active').length;
        const rated = document.querySelectorAll('.stars-indicator.active').length;
 
        document.getElementById('statTotalPhotos').textContent = total;
        document.getElementById('statSelectedCount').textContent = selected;
        document.getElementById('statLovedCount').textContent = loved;
        document.getElementById('statRatedCount').textContent = rated;
    }
 
    initLightroomFilter();
    startAdminPolling();
</script>
<?= $this->endSection() ?>
