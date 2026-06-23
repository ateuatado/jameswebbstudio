<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    .client-hub { margin-top: 80px; min-height: 80vh; }

    /* ── Tab Nav ── */
    .hub-tabs {
        background: rgba(0,0,0,0.6);
        border-bottom: 1px solid rgba(197,160,89,.15);
        backdrop-filter: blur(10px);
        position: sticky; top: 72px; z-index: 100;
    }
    .hub-tabs-inner {
        display: flex;
        gap: 0;
        overflow-x: auto;
        scrollbar-width: none;
        max-width: 960px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    .hub-tabs-inner::-webkit-scrollbar { display: none; }
    .hub-tab {
        display: flex; align-items: center; gap: 8px;
        padding: 1rem 1.4rem;
        font-family: 'Inter', sans-serif;
        font-size: .72rem;
        letter-spacing: .15em;
        text-transform: uppercase;
        color: rgba(255,255,255,.4);
        text-decoration: none;
        border-bottom: 2px solid transparent;
        transition: all .25s;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .hub-tab:hover { color: rgba(197,160,89,.7); }
    .hub-tab.active {
        color: #C5A059;
        border-bottom-color: #C5A059;
    }
    .hub-tab .tab-count {
        background: rgba(197,160,89,.15);
        color: #C5A059;
        font-size: .58rem;
        padding: 2px 7px;
        border-radius: 20px;
        font-weight: 700;
    }

    /* ── Conteúdo das Abas ── */
    .hub-content { max-width: 960px; margin: 0 auto; padding: 2.5rem 1rem 4rem; }

    /* ── Header da aba ── */
    .tab-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(197,160,89,.12);
    }
    .tab-header h2 {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: 1.6rem; font-weight: 400;
        color: #fff; margin: 0 0 4px;
    }
    .tab-header p {
        font-family: 'Inter', sans-serif;
        font-size: .75rem; color: rgba(255,255,255,.35);
        letter-spacing: .1em; margin: 0;
    }

    /* ── Card de Ensaio ── */
    .ensaio-card {
        background: rgba(255,255,255,.03);
        border: 1px solid rgba(255,255,255,.08);
        padding: 24px; margin-bottom: 16px;
        transition: all .3s; position: relative; overflow: hidden;
        border-radius: 6px;
    }
    .ensaio-card::before {
        content: ''; position: absolute;
        top: 0; left: 0; width: 3px; height: 100%;
        background: var(--accent-color, rgba(197,160,89,.4));
    }
    .ensaio-card:hover { background: rgba(255,255,255,.05); border-color: rgba(197,160,89,.2); }
    .ensaio-card .card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
    .ensaio-card .card-title { font-family: 'EB Garamond', Georgia, serif; font-size: 1.2rem; color: #fff; margin: 0 0 4px; }
    .ensaio-card .card-meta { font-family: 'Inter', sans-serif; font-size: .72rem; color: rgba(255,255,255,.4); margin: 0; }
    .ensaio-card .card-actions { margin-top: 16px; display: flex; gap: 10px; flex-wrap: wrap; }

    /* ── Status badges ── */
    .status-badge {
        display: inline-flex; align-items: center; gap: 6px;
        font-family: 'Inter', sans-serif; font-size: .62rem;
        letter-spacing: .1em; text-transform: uppercase;
        padding: 5px 12px; border-radius: 20px; white-space: nowrap;
    }
    .status-badge.approved  { background: rgba(46,125,50,.15); color: #66bb6a; }
    .status-badge.pending   { background: rgba(255,183,77,.12); color: #ffb74d; }
    .status-badge.open,
    .status-badge.selecting { background: rgba(100,181,246,.12); color: #64b5f6; }
    .status-badge.completed { background: rgba(255,255,255,.06); color: rgba(255,255,255,.5); }

    /* ── Botões de ação ── */
    .btn-ensaio {
        font-family: 'Inter', sans-serif; font-size: .67rem;
        letter-spacing: .12em; text-transform: uppercase;
        padding: 9px 20px; text-decoration: none;
        border: 1px solid rgba(197,160,89,.35); color: #C5A059;
        background: transparent; border-radius: 4px; transition: all .2s;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-ensaio:hover { background: rgba(197,160,89,.08); color: #C5A059; }
    .btn-ensaio.primary {
        background: linear-gradient(135deg, #C5A059, #F5E27A);
        color: #000; border-color: transparent; font-weight: 700;
    }
    .btn-ensaio.primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(197,160,89,.25); color: #000; }

    /* ── Galeria grid ── */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.25rem;
    }
    .gallery-card {
        background: rgba(255,255,255,.03);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 8px; overflow: hidden;
        transition: all .3s; text-decoration: none;
        display: flex; flex-direction: column;
    }
    .gallery-card:hover { border-color: rgba(197,160,89,.35); transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,.4); }
    .gallery-card-cover {
        height: 160px; background: #111;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem; color: rgba(197,160,89,.2);
    }
    .gallery-card-body { padding: 1rem; flex: 1; }
    .gallery-card-title { font-family: 'EB Garamond', Georgia, serif; font-size: 1.1rem; color: #fff; margin: 0 0 4px; }
    .gallery-card-meta { font-family: 'Inter', sans-serif; font-size: .7rem; color: rgba(255,255,255,.35); margin: 0; }
    .gallery-card-footer { padding: .75rem 1rem; border-top: 1px solid rgba(255,255,255,.05); display: flex; align-items: center; justify-content: space-between; }

    /* ── Busca ── */
    .search-box {
        display: flex; gap: .75rem; margin-bottom: 2rem;
    }
    .search-input {
        flex: 1; background: rgba(255,255,255,.04) !important;
        border: 1px solid rgba(197,160,89,.3) !important;
        color: #fff !important; padding: .8rem 1.1rem !important;
        border-radius: 8px !important; font-family: 'Inter', sans-serif;
        font-size: .9rem !important; transition: border-color .3s;
    }
    .search-input:focus { border-color: #C5A059 !important; outline: none !important; box-shadow: 0 0 0 3px rgba(197,160,89,.1) !important; }
    .btn-buscar {
        background: #C5A059; color: #000; font-weight: 700;
        border: none; padding: .8rem 1.6rem; border-radius: 8px;
        font-family: 'Inter', sans-serif; font-size: .8rem;
        letter-spacing: .1em; text-transform: uppercase;
        cursor: pointer; transition: background .2s; white-space: nowrap;
    }
    .btn-buscar:hover { background: #d4b06a; }
    .photo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem; }
    .photo-card { border-radius: 8px; overflow: hidden; background: #111; border: 1px solid rgba(255,255,255,.07); cursor: zoom-in; transition: all .3s; }
    .photo-card:hover { border-color: #C5A059; transform: translateY(-2px); }
    .photo-card img { width: 100%; height: 200px; object-fit: contain; background: #000; display: block; }
    .photo-card-body { padding: 8px 10px; }
    .project-pill { display: inline-block; background: rgba(197,160,89,.1); color: #C5A059; font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; padding: 2px 8px; border-radius: 20px; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .tag { background: #1a1a1a; color: #666; font-size: .58rem; padding: 2px 6px; border-radius: 6px; }
    .tag.hl { background: rgba(197,160,89,.15); color: #C5A059; }
    #searchStatus { font-family: 'Inter', sans-serif; font-size: .8rem; color: rgba(255,255,255,.4); margin-bottom: 1rem; min-height: 1.2em; }

    /* ── Perfil ── */
    .perfil-form { max-width: 560px; }
    .perfil-section-title {
        font-family: 'Inter', sans-serif;
        font-size: .65rem; letter-spacing: .2em;
        text-transform: uppercase; color: #C5A059;
        margin-bottom: 1rem;
        padding-bottom: .5rem;
        border-bottom: 1px solid rgba(197,160,89,.15);
    }
    .perfil-form label {
        font-family: 'Inter', sans-serif;
        font-size: .72rem; letter-spacing: .08em;
        text-transform: uppercase; color: rgba(255,255,255,.45);
        margin-bottom: 5px; display: block;
    }
    .field-hint {
        font-size: .6rem; text-transform: none;
        letter-spacing: 0; opacity: .5;
    }
    .perfil-row {
        display: flex; gap: 1rem; margin-bottom: 0;
    }
    .perfil-row > div { flex: 1; }
    .perfil-form input {
        width: 100%; background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.1); color: #fff;
        padding: .7rem .9rem; border-radius: 6px;
        font-family: 'Inter', sans-serif; font-size: .88rem;
        margin-bottom: 1.1rem; transition: border-color .2s;
    }
    .perfil-form input:focus { border-color: rgba(197,160,89,.5); outline: none; }
    .perfil-form input[readonly] { opacity: .45; cursor: not-allowed; }
    .perfil-email {
        font-family: 'Inter', sans-serif; font-size: .85rem;
        color: rgba(255,255,255,.35); padding: .7rem .9rem;
        background: rgba(255,255,255,.02);
        border: 1px solid rgba(255,255,255,.06);
        border-radius: 6px; margin-bottom: 1.1rem;
    }

    /* ── Empty state ── */
    .empty-state { text-align: center; padding: 3rem 1.5rem; border: 1px dashed rgba(255,255,255,.08); border-radius: 8px; }
    .empty-state .emoji { font-size: 2.5rem; margin-bottom: 1rem; }
    .empty-state p { font-family: 'Inter', sans-serif; font-size: .85rem; color: rgba(255,255,255,.35); margin: 0; }

    /* ── Lightbox ── */
    .lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.93); z-index: 9999; align-items: center; justify-content: center; flex-direction: column; gap: 1rem; }
    .lightbox.active { display: flex; }
    .lightbox img { max-width: 90vw; max-height: 80vh; object-fit: contain; border-radius: 6px; }
    .lightbox-close { position: absolute; top: 1.5rem; right: 1.5rem; color: #aaa; font-size: 1.5rem; cursor: pointer; background: none; border: none; }
    .lightbox-close:hover { color: #C5A059; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php $user = auth()->user(); ?>

<div class="client-hub">

    <!-- ── Barra de Abas ── -->
    <nav class="hub-tabs">
        <div class="hub-tabs-inner">
            <a href="?tab=ensaios" class="hub-tab <?= $tab === 'ensaios' ? 'active' : '' ?>">
                <i class="fas fa-clipboard-list"></i> Meus Ensaios
                <?php if (count($orders)): ?>
                    <span class="tab-count"><?= count($orders) ?></span>
                <?php endif ?>
            </a>
            <a href="?tab=galeria" class="hub-tab <?= $tab === 'galeria' ? 'active' : '' ?>">
                <i class="fas fa-images"></i> Galeria
                <?php if (count($projects)): ?>
                    <span class="tab-count"><?= count($projects) ?></span>
                <?php endif ?>
            </a>
            <a href="?tab=busca" class="hub-tab <?= $tab === 'busca' ? 'active' : '' ?>">
                <i class="fas fa-search"></i> Busca de Fotos
            </a>
            <a href="?tab=perfil" class="hub-tab <?= $tab === 'perfil' ? 'active' : '' ?>">
                <i class="fas fa-user"></i> Meu Perfil
            </a>
        </div>
    </nav>

    <div class="hub-content">

        <!-- ════════════════════════════════════════════
             ABA: MEUS ENSAIOS
             ════════════════════════════════════════════ -->
        <?php if ($tab === 'ensaios'): ?>

            <div class="tab-header">
                <h2>Meus Ensaios</h2>
                <p>Suas compras, agendamentos e documentos</p>
            </div>

            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <?php
                        $statusLabel = [
                            'approved'  => '✅ Pago',
                            'pending'   => '⏳ Aguardando pagamento',
                            'cancelled' => '❌ Cancelado',
                        ][$order->status] ?? $order->status;
                        $packageName = $order->package ? $order->package->name : 'Ensaio Fotográfico';
                        $amount      = 'R$ ' . number_format((float) $order->amount, 2, ',', '.');
                        $date        = date('d/m/Y', strtotime($order->created_at));
                    ?>
                    <div class="ensaio-card" style="--accent-color: <?= $order->status === 'approved' ? '#66bb6a' : '#ffb74d' ?>">
                        <div class="card-top">
                            <div>
                                <h4 class="card-title"><?= esc($packageName) ?></h4>
                                <p class="card-meta"><?= $amount ?> &middot; Comprado em <?= $date ?></p>
                            </div>
                            <span class="status-badge <?= esc($order->status) ?>"><?= $statusLabel ?></span>
                        </div>
                        <div class="card-actions">
                            <?php if ($order->status === 'approved' && !empty($order->agenda_link)): ?>
                                <a href="<?= esc($order->agenda_link) ?>" class="btn-ensaio primary" target="_blank">
                                    <i class="fas fa-calendar-alt"></i> Agendar Ensaio
                                </a>
                            <?php elseif ($order->status === 'pending'): ?>
                                <span class="btn-ensaio" style="opacity:.4;cursor:default;">Aguardando confirmação do banco</span>
                            <?php else: ?>
                                <span class="btn-ensaio" style="opacity:.4;cursor:default;"><i class="fas fa-check"></i> Ensaio confirmado</span>
                            <?php endif ?>
                            <?php if ($order->status === 'approved'): ?>
                                <a href="<?= site_url('client/guia-pre-ensaio/' . $order->id) ?>" class="btn-ensaio" target="_blank">
                                    <i class="fas fa-file-alt"></i> Guia Pré-Ensaio
                                </a>
                                <a href="<?= site_url('client/contrato/' . $order->id) ?>" class="btn-ensaio" target="_blank">
                                    <i class="fas fa-file-contract"></i> Meu Contrato
                                </a>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="emoji">🛒</div>
                    <p>Nenhum ensaio adquirido ainda.</p>
                </div>
            <?php endif ?>


        <!-- ════════════════════════════════════════════
             ABA: GALERIA
             ════════════════════════════════════════════ -->
        <?php elseif ($tab === 'galeria'): ?>

            <div class="tab-header">
                <h2>Minha Galeria</h2>
                <p>Seus ensaios realizados — clique para acessar as fotos</p>
            </div>

            <?php if (!empty($projects)): ?>
                <div class="gallery-grid">
                    <?php foreach ($projects as $proj): ?>
                        <?php
                            $statusLabel = [
                                'open'      => 'Aberta',
                                'selecting' => 'Selecionando',
                                'paid'      => 'Paga',
                                'completed' => 'Concluída',
                            ][$proj->status] ?? $proj->status;
                            $statusColor = in_array($proj->status, ['open','selecting']) ? '#64b5f6' : 'rgba(255,255,255,.4)';
                        ?>
                        <a href="<?= site_url('client/galeria/' . $proj->id) ?>" class="gallery-card">
                            <div class="gallery-card-cover">
                                <i class="fas fa-images"></i>
                            </div>
                            <div class="gallery-card-body">
                                <div class="gallery-card-title"><?= esc($proj->name ?? 'Ensaio #' . $proj->id) ?></div>
                                <div class="gallery-card-meta">
                                    <?= $proj->photo_count ?> foto<?= $proj->photo_count !== 1 ? 's' : '' ?>
                                    &middot; <?= date('d/m/Y', strtotime($proj->created_at)) ?>
                                </div>
                            </div>
                            <div class="gallery-card-footer">
                                <span style="font-family:'Inter',sans-serif;font-size:.65rem;color:<?= $statusColor ?>;text-transform:uppercase;letter-spacing:.1em;">
                                    <?= esc($statusLabel) ?>
                                </span>
                                <span style="color:#C5A059;font-size:.8rem;"><i class="fas fa-arrow-right"></i></span>
                            </div>
                        </a>
                    <?php endforeach ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="emoji">📷</div>
                    <p>Nenhuma galeria disponível ainda.</p>
                </div>
            <?php endif ?>


        <!-- ════════════════════════════════════════════
             ABA: BUSCA
             ════════════════════════════════════════════ -->
        <?php elseif ($tab === 'busca'): ?>

            <div class="tab-header">
                <h2>Busca de Fotos</h2>
                <p>Pesquise por elementos, poses ou momentos nas suas fotos</p>
            </div>

            <div class="search-box">
                <input type="text" id="searchInput" class="search-input"
                       placeholder="Ex: sorrindo, vestido, jardim, olhando para cima..."
                       autofocus>
                <button class="btn-buscar" onclick="doSearch()">
                    <i class="fas fa-search me-1"></i> Buscar
                </button>
            </div>

            <div id="searchStatus"></div>
            <div id="photoGrid" class="photo-grid"></div>

            <!-- Sugestões iniciais -->
            <div id="suggestions" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:1rem;">
                <?php foreach (['sorrindo','vestido','pose','olhando','jardim','estúdio','família','retrato'] as $s): ?>
                    <button onclick="searchFor('<?= $s ?>')"
                        style="background:#141414;color:#666;border:1px solid #2a2a2a;border-radius:20px;padding:4px 14px;font-size:.78rem;cursor:pointer;transition:all .2s;"
                        onmouseover="this.style.borderColor='#C5A059';this.style.color='#C5A059'"
                        onmouseout="this.style.borderColor='#2a2a2a';this.style.color='#666'">
                        <?= $s ?>
                    </button>
                <?php endforeach ?>
            </div>


        <!-- ════════════════════════════════════════════
             ABA: PERFIL
             ════════════════════════════════════════════ -->
        <?php elseif ($tab === 'perfil'): ?>

            <div class="tab-header">
                <h2>Meu Perfil</h2>
                <p>Seus dados pessoais e endereço para contratos</p>
            </div>

            <?php if (session()->getFlashdata('perfil_ok')): ?>
                <div style="font-size:.85rem;background:rgba(46,125,50,.15);border:1px solid rgba(46,125,50,.3);color:#66bb6a;padding:12px 16px;border-radius:6px;margin-bottom:1.5rem;">
                    ✅ Perfil atualizado com sucesso!
                </div>
            <?php endif ?>

            <form class="perfil-form" method="post" action="<?= site_url('client/perfil/salvar') ?>">
                <?= csrf_field() ?>

                <div class="perfil-section-title">Identificação</div>

                <label>Como gosta de ser chamado(a)</label>
                <input type="text" name="display_name" value="<?= esc($displayName) ?>"
                       placeholder="Nome que aparece no sistema">

                <label>Apelidos <span class="field-hint">separados por vírgula</span></label>
                <input type="text" name="nicknames" value="<?= esc($nicknames) ?>"
                       placeholder="Ex: Bê, Beta, Beatriz...">

                <label>E-mail</label>
                <div class="perfil-email"><?= esc($user->email) ?></div>

                <div class="perfil-section-title" style="margin-top:2rem;">Dados para contrato</div>

                <label>Nome completo <span class="field-hint">conforme documento</span></label>
                <input type="text" name="nome_completo" value="<?= esc($nomeCompleto) ?>"
                       placeholder="Nome como está no RG ou CNH">

                <div class="perfil-row">
                    <div>
                        <label>CPF</label>
                        <input type="text" name="cpf" id="cpf" value="<?= esc($cpf) ?>"
                               placeholder="000.000.000-00" maxlength="14">
                    </div>
                    <div>
                        <label>RG</label>
                        <input type="text" name="rg" value="<?= esc($rg) ?>"
                               placeholder="00.000.000-0">
                    </div>
                </div>

                <div class="perfil-section-title" style="margin-top:2rem;">Endereço</div>

                <div class="perfil-row">
                    <div style="max-width:160px;">
                        <label>CEP</label>
                        <div style="position:relative;">
                            <input type="text" name="endereco_cep" id="cep"
                                   value="<?= esc($enderecoCep) ?>"
                                   placeholder="00000-000" maxlength="9">
                            <span id="cepSpinner" style="display:none;position:absolute;right:10px;top:50%;transform:translateY(-50%);color:#C5A059;">⟳</span>
                        </div>
                    </div>
                    <div style="flex:1;">
                        <label>Logradouro <span class="field-hint">preenchido pelo CEP</span></label>
                        <input type="text" name="endereco_logradouro" id="logradouro"
                               value="<?= esc($enderecoLogradouro) ?>" placeholder="Rua, Av, Alameda...">
                    </div>
                </div>

                <div class="perfil-row">
                    <div style="max-width:120px;">
                        <label>Número</label>
                        <input type="text" name="endereco_numero" id="numero"
                               value="<?= esc($enderecoNumero) ?>" placeholder="123">
                    </div>
                    <div style="flex:1;">
                        <label>Complemento <span class="field-hint">opcional</span></label>
                        <input type="text" name="endereco_complemento"
                               value="<?= esc($enderecoComplemento) ?>" placeholder="Apto, Bloco...">
                    </div>
                </div>

                <div class="perfil-row">
                    <div style="flex:1;">
                        <label>Cidade <span class="field-hint">preenchido pelo CEP</span></label>
                        <input type="text" name="endereco_cidade" id="cidade"
                               value="<?= esc($enderecoCidade) ?>" placeholder="Cidade">
                    </div>
                    <div style="max-width:80px;">
                        <label>Estado</label>
                        <input type="text" name="endereco_estado" id="estado"
                               value="<?= esc($enderecoEstado) ?>" placeholder="SP" maxlength="2"
                               style="text-transform:uppercase;">
                    </div>
                </div>

                <button type="submit" class="btn-ensaio primary" style="margin-top:.5rem;">
                    <i class="fas fa-save"></i> Salvar alterações
                </button>
            </form>

        <?php endif ?>

    </div><!-- /hub-content -->
</div>

<!-- Lightbox -->
<div class="lightbox" id="lightbox" onclick="closeLightbox()">
    <button class="lightbox-close" onclick="closeLightbox()">✕</button>
    <img id="lightboxImg" src="" alt="">
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const searchUrl = '<?= site_url('client/buscar') ?>';

function searchFor(q) {
    document.getElementById('searchInput').value = q;
    doSearch();
}

document.getElementById('searchInput')?.addEventListener('keydown', e => {
    if (e.key === 'Enter') doSearch();
});

async function doSearch() {
    const q = document.getElementById('searchInput').value.trim();
    if (!q) return;

    document.getElementById('suggestions').style.display = 'none';
    document.getElementById('searchStatus').textContent  = 'Buscando...';
    document.getElementById('photoGrid').innerHTML        = '';

    const res  = await fetch(`${searchUrl}?q=${encodeURIComponent(q)}`);
    const data = await res.json();

    const status = document.getElementById('searchStatus');
    const grid   = document.getElementById('photoGrid');

    if (data.total === 0) {
        status.textContent = `Nenhuma foto encontrada para "${q}"`;
        return;
    }

    status.innerHTML = `<strong style="color:#fff">${data.total}</strong> foto${data.total !== 1 ? 's' : ''} encontrada${data.total !== 1 ? 's' : ''} para <strong style="color:#C5A059">"${q}"</strong>`;

    data.results.forEach(photo => {
        const qLower = q.toLowerCase();
        const tags   = (photo.ai_tags || '').split(',').slice(0, 6)
            .map(t => t.trim()).filter(Boolean)
            .map(t => `<span class="tag ${t.toLowerCase().includes(qLower) ? 'hl' : ''}">${t}</span>`)
            .join(' ');

        grid.insertAdjacentHTML('beforeend', `
            <div class="photo-card" onclick="openLightbox('${photo.presigned_url}')">
                <img src="${photo.presigned_url}" alt="${photo.original_filename}" loading="lazy">
                <div class="photo-card-body">
                    <span class="project-pill">📁 ${photo.project_name}</span>
                    <div style="margin-top:6px;display:flex;flex-wrap:wrap;gap:3px;">${tags}</div>
                </div>
            </div>
        `);
    });
}

function openLightbox(url) {
    document.getElementById('lightboxImg').src = url;
    document.getElementById('lightbox').classList.add('active');
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

// ── ViaCEP auto-fill ──
const cepInput = document.getElementById('cep');
if (cepInput) {
    cepInput.addEventListener('input', () => {
        let v = cepInput.value.replace(/\D/g, '');
        if (v.length > 5) v = v.slice(0,5) + '-' + v.slice(5,8);
        cepInput.value = v;
    });
    cepInput.addEventListener('blur', async () => {
        const cep = cepInput.value.replace(/\D/g, '');
        if (cep.length !== 8) return;
        const spinner = document.getElementById('cepSpinner');
        if (spinner) spinner.style.display = 'inline';
        try {
            const res  = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            const data = await res.json();
            if (!data.erro) {
                document.getElementById('logradouro').value = data.logradouro || '';
                document.getElementById('cidade').value     = data.localidade  || '';
                const est = document.getElementById('estado');
                if (est) est.value = data.uf || '';
                const num = document.getElementById('numero');
                if (num) num.focus();
            }
        } catch(e) {}
        if (spinner) spinner.style.display = 'none';
    });
}

// ── Máscara CPF ──
const cpfEl = document.getElementById('cpf');
if (cpfEl) {
    cpfEl.addEventListener('input', () => {
        let v = cpfEl.value.replace(/\D/g,'');
        if (v.length > 9)      v = v.slice(0,3)+'.'+v.slice(3,6)+'.'+v.slice(6,9)+'-'+v.slice(9,11);
        else if (v.length > 6) v = v.slice(0,3)+'.'+v.slice(3,6)+'.'+v.slice(6);
        else if (v.length > 3) v = v.slice(0,3)+'.'+v.slice(3);
        cpfEl.value = v;
    });
}
</script>
<?= $this->endSection() ?>
