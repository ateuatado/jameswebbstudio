<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
/* ── Pricing Hero ── */
.pricing-hero {
    padding: 140px 24px 60px;
    background: #000;
    text-align: center;
}
.pricing-eyebrow {
    font-family: 'Inter', sans-serif;
    font-size: .6rem; letter-spacing: .35em;
    text-transform: uppercase; color: #C5A059;
    margin-bottom: 16px; display: block;
}
.pricing-title {
    font-family: 'EB Garamond', Georgia, serif;
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 400; color: #fff;
    line-height: 1.15; margin-bottom: 16px;
}
.pricing-sub {
    font-family: 'EB Garamond', Georgia, serif;
    font-size: clamp(.95rem, 2vw, 1.2rem);
    color: rgba(255,255,255,.4); font-style: italic;
    max-width: 600px; margin: 0 auto;
    line-height: 1.6;
}

/* ── Category Sections ── */
.cat-section { padding: 60px 0 40px; }
.cat-title {
    font-family: 'EB Garamond', Georgia, serif;
    font-size: 1.5rem; color: rgba(197,160,89,.7);
    text-align: center; letter-spacing: .1em;
    text-transform: uppercase; margin-bottom: 10px;
    padding-bottom: 0;
}
.cat-desc {
    font-family: 'EB Garamond', Georgia, serif;
    font-style: italic;
    font-size: clamp(.88rem, 1.8vw, 1.05rem);
    color: rgba(255,255,255,.3);
    text-align: center;
    max-width: 620px;
    margin: 0 auto 36px;
    line-height: 1.65;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(197,160,89,.1);
}

/* ── Package Cards (idêntico ao hero_page) ── */
.pkg-card{background:#0a0a0a;border:1px solid rgba(255,255,255,.1);padding:36px 28px 28px;position:relative;transition:border-color .3s,transform .3s;height:100%;display:flex;flex-direction:column;}
.pkg-card:hover{border-color:rgba(197,160,89,.35);transform:translateY(-4px);}
.pkg-preferred{border-color:rgba(197,160,89,.55)!important;background:linear-gradient(160deg,#0e0c07 0%,#0a0a0a 100%);}
.pkg-badge{position:absolute;top:-1px;left:50%;transform:translateX(-50%);background:linear-gradient(90deg,#C5A059,#F5E27A);color:#000;font-family:'Inter',sans-serif;font-size:.6rem;font-weight:700;letter-spacing:.15em;padding:4px 16px;white-space:nowrap;}
.pkg-name{font-family:'Inter',sans-serif;font-size:.7rem;letter-spacing:.2em;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:16px;margin-top:12px;}
.pkg-price{font-family:'EB Garamond',Georgia,serif;font-size:clamp(2.4rem,5vw,3.4rem);color:#fff;font-weight:400;line-height:1;margin-bottom:4px;}
.pkg-currency{font-size:1.1rem;color:#C5A059;vertical-align:super;margin-right:4px;}
.pkg-photos{font-family:'Inter',sans-serif;font-size:.78rem;color:#C5A059;letter-spacing:.08em;margin-bottom:16px;}
.pkg-extra{font-family:'Inter',sans-serif;font-size:.7rem;color:rgba(255,255,255,.3);margin-bottom:24px;letter-spacing:.05em;}
.pkg-btn-buy{width:100%;background:linear-gradient(135deg,#C5A059,#F5E27A,#C5A059);background-size:200%;color:#000;border:none;padding:14px;font-family:'Inter',sans-serif;font-size:.68rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;cursor:pointer;transition:background-position .4s;margin-bottom:10px;}
.pkg-btn-buy:hover{background-position:100%;}

/* ── Service Checklist ── */
.pkg-services { margin: 12px 0 20px; padding: 0; list-style: none; flex: 1; }
.pkg-services li {
    display: flex; align-items: flex-start; gap: 8px;
    padding: 5px 0; font-family: 'Inter', sans-serif;
    font-size: .72rem; color: rgba(255,255,255,.5);
    line-height: 1.35; position: relative;
    border-bottom: 1px solid rgba(255,255,255,.03);
}
.pkg-services li:last-child { border-bottom: none; }
.pkg-services .svc-check {
    color: #C5A059; font-size: .65rem;
    flex-shrink: 0; margin-top: 1px;
}
.pkg-services .svc-label { flex: 1; }
.pkg-services li:hover { color: rgba(255,255,255,.8); }
.pkg-services .svc-tooltip {
    display: none; position: absolute;
    bottom: 100%; left: 20px; right: 0;
    background: #1a1a1a; border: 1px solid rgba(197,160,89,.25);
    color: rgba(255,255,255,.65); padding: 8px 12px;
    font-size: .65rem; line-height: 1.45; z-index: 10;
    pointer-events: none;
}
.pkg-services li:hover .svc-tooltip { display: block; }
.pkg-phase-label {
    font-size: .55rem; letter-spacing: .12em; text-transform: uppercase;
    color: rgba(197,160,89,.45); margin-top: 8px; padding-top: 6px;
    border-top: 1px solid rgba(197,160,89,.08);
}
.pkg-phase-label:first-child { margin-top: 0; border-top: none; padding-top: 0; }

/* ── Divider ── */
.lp-divider { width: 40px; height: 2px; background: #C5A059; margin: 0 auto; }

/* ── CTA Bottom ── */
.pricing-cta {
    border-top: 1px solid rgba(255,255,255,.06);
    padding: 60px 0; text-align: center;
}
.pricing-cta-text {
    font-family: 'EB Garamond', Georgia, serif;
    font-style: italic; font-size: clamp(1.1rem,2.5vw,1.4rem);
    color: rgba(255,255,255,.4); margin-bottom: 24px;
}
.pricing-cta-btn {
    background: transparent; border: 1px solid rgba(255,255,255,.15);
    color: rgba(255,255,255,.45); padding: 14px 40px;
    font-family: 'Inter', sans-serif; font-size: .7rem;
    letter-spacing: .15em; text-transform: uppercase;
    cursor: pointer; transition: all .25s;
}
.pricing-cta-btn:hover { border-color: rgba(197,160,89,.4); color: rgba(197,160,89,.8); }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="max-width:1400px;margin:0 auto;background:#000;">

<!-- ── Hero ── -->
<section class="pricing-hero">
    <div class="lp-divider mb-4"></div>
    <span class="pricing-eyebrow">INVESTIMENTO</span>
    <h1 class="pricing-title">Seu ensaio, do <em>seu</em> jeito</h1>
    <p class="pricing-sub">
        Conheça nossos pacotes e escolha a experiência ideal para registrar quem você é.
        Cada detalhe é planejado para que a sua imagem fale por você.
    </p>
</section>

<!-- ── Pacotes por Categoria ── -->
<section style="background:#000;padding:0 0 60px;">
  <div class="container px-4">

    <?php foreach ($grouped as $catName => $packages): ?>
    <div class="cat-section">
      <h2 class="cat-title"><?= esc($catName) ?></h2>
      <?php if (!empty($catDescMap[$catName] ?? '')): ?>
        <p class="cat-desc"><?= esc($catDescMap[$catName]) ?></p>
      <?php endif; ?>

      <div class="row justify-content-center g-4">
        <?php foreach ($packages as $pkg): ?>
        <div class="col-md-4">
          <div class="pkg-card <?= $pkg->is_preferred ? 'pkg-preferred' : '' ?>">
            <?php if ($pkg->is_preferred): ?><div class="pkg-badge">MAIS ESCOLHIDO</div><?php endif; ?>
            <div class="pkg-name"><?= esc($pkg->name) ?></div>
            <div class="pkg-price"><span class="pkg-currency">R$</span><?= number_format($pkg->base_price, 0, ',', '.') ?></div>
            <div class="pkg-photos"><?= (int)$pkg->included_photos ?> fotos tratadas</div>

            <?php if (!empty($pkg->services)): ?>
            <ul class="pkg-services">
              <?php
                $currentPhase = '';
                $phaseLabels = \App\Models\ServiceModel::PHASE_LABELS;
              ?>
              <?php foreach ($pkg->services as $svc): ?>
                <?php if ($svc->phase !== $currentPhase): ?>
                  <?php $currentPhase = $svc->phase; ?>
                  <li class="pkg-phase-label"><?= esc($phaseLabels[$currentPhase] ?? $currentPhase) ?></li>
                <?php endif; ?>
                <li>
                  <span class="svc-check">✓</span>
                  <span class="svc-label"><?= esc($svc->name) ?></span>
                  <?php if (!empty($svc->description)): ?>
                    <span class="svc-tooltip"><?= esc($svc->description) ?></span>
                  <?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>

            <?php if ($pkg->extra_photo_price > 0): ?>
              <div class="pkg-extra">+ fotos por R$ <?= number_format($pkg->extra_photo_price, 0, ',', '.') ?> cada</div>
            <?php endif; ?>
            <button class="pkg-btn-buy" onclick="openCheckout(<?= $pkg->id ?>,'<?= esc($pkg->name) ?>',<?= $pkg->base_price ?>)">ESCOLHER ESTE PACOTE</button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- CTA final -->
    <div class="pricing-cta">
      <p class="pricing-cta-text">Ainda tem dúvidas? Converse comigo antes de decidir.</p>
      <button class="pricing-cta-btn" onclick="openTalk(0,'Geral')">CONVERSAR ANTES DE COMPRAR</button>
    </div>

  </div>
</section>

</div><!-- /container -->

<!-- ══ MODAL CHECKOUT ════════════════════════════════════════════════ -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
    <div class="modal-content" style="background:#0a0a0a;border:1px solid rgba(197,160,89,.25);border-radius:0;">
      <div class="modal-header" style="border-bottom:1px solid rgba(197,160,89,.12);padding:24px 28px 20px;">
        <div>
          <p style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.2em;text-transform:uppercase;color:rgba(197,160,89,.6);margin:0 0 4px;">PACOTE SELECIONADO</p>
          <h5 id="checkoutPkgName" style="font-family:'EB Garamond',Georgia,serif;font-size:1.6rem;color:#fff;margin:0;font-weight:400;"></h5>
          <p id="checkoutPkgPrice" style="font-family:'Inter',sans-serif;font-size:.9rem;color:#C5A059;margin:4px 0 0;font-weight:500;"></p>
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding:28px;">
        <form id="checkoutForm">
          <?= csrf_field() ?>
          <input type="hidden" id="chk_package_id" name="package_id">
          <input type="hidden" id="chk_hero_id" name="hero_id" value="0">
          <div class="mb-3">
            <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">SEU NOME</label>
            <input type="text" name="name" required placeholder="Nome completo" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;">
          </div>
          <div class="mb-3">
            <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">E-MAIL</label>
            <input type="email" name="email" required placeholder="seu@email.com" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;">
          </div>
          <div class="mb-3">
            <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">WHATSAPP</label>
            <input type="tel" name="phone" placeholder="(00) 00000-0000" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;">
          </div>

          <!-- Dados para o contrato -->
          <div style="border-top:1px solid rgba(197,160,89,.12);padding-top:16px;margin-bottom:4px;">
            <p style="font-family:'Inter',sans-serif;font-size:.6rem;letter-spacing:.15em;text-transform:uppercase;color:rgba(197,160,89,.5);margin:0 0 12px;">DADOS PARA O CONTRATO</p>
          </div>
          <div style="display:flex;gap:10px;margin-bottom:12px;">
            <div style="flex:1;">
              <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">CPF</label>
              <input type="text" name="cpf" required placeholder="000.000.000-00" maxlength="14"
                     oninput="let v=this.value.replace(/\D/g,'');if(v.length>3)v=v.slice(0,3)+'.'+v.slice(3);if(v.length>7)v=v.slice(0,7)+'.'+v.slice(7);if(v.length>11)v=v.slice(0,11)+'-'+v.slice(11);this.value=v.slice(0,14);"
                     style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;font-variant-numeric:tabular-nums;">
            </div>
            <div style="flex:1;">
              <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">RG</label>
              <input type="text" name="rg" placeholder="00.000.000-0" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;">
            </div>
            <div style="flex:0 0 130px;">
              <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">ESTADO CIVIL</label>
              <select name="marital_status" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.7);padding:12px 10px;font-size:.85rem;outline:none;appearance:auto;">
                <option value="">—</option>
                <option value="Solteiro(a)">Solteiro(a)</option>
                <option value="Casado(a)">Casado(a)</option>
                <option value="Divorciado(a)">Divorciado(a)</option>
                <option value="Viúvo(a)">Viúvo(a)</option>
                <option value="União Estável">União Estável</option>
              </select>
            </div>
          </div>
          <div style="display:flex;gap:10px;margin-bottom:12px;">
            <div style="flex:0 0 120px;">
              <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">CEP</label>
              <input type="text" name="zip_code" id="prcZip" placeholder="00000-000" maxlength="9"
                     oninput="let v=this.value.replace(/\D/g,'');if(v.length>5)v=v.slice(0,5)+'-'+v.slice(5);this.value=v.slice(0,9);if(v.replace('-','').length===8)fetchCep(v.replace('-',''),'prc');"
                     style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;font-variant-numeric:tabular-nums;">
            </div>
            <div style="flex:1;">
              <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">ENDEREÇO</label>
              <input type="text" name="address" id="prcAddr" placeholder="Rua, nº, complemento" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;">
            </div>
          </div>
          <div style="display:flex;gap:10px;margin-bottom:16px;">
            <div style="flex:1;">
              <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">BAIRRO</label>
              <input type="text" name="neighborhood" id="prcNeigh" placeholder="Bairro" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;">
            </div>
            <div style="flex:1;">
              <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">CIDADE</label>
              <input type="text" name="city" id="prcCity" placeholder="Cidade" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;">
            </div>
            <div style="flex:0 0 60px;">
              <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">UF</label>
              <input type="text" name="state" id="prcState" placeholder="SP" maxlength="2" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;text-transform:uppercase;">
            </div>
          </div>

          <!-- Termos do contrato -->
          <div style="margin-bottom:16px;">
            <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;font-family:'Inter',sans-serif;font-size:.78rem;color:rgba(255,255,255,.55);line-height:1.5;">
              <input type="checkbox" name="accept_terms" required style="margin-top:3px;accent-color:#C5A059;min-width:16px;">
              Li e aceito os <a href="#" onclick="event.preventDefault();document.getElementById('termsBoxP').style.display=document.getElementById('termsBoxP').style.display==='none'?'block':'none'" style="color:#C5A059;text-decoration:underline;">termos do contrato</a> de prestação de serviços fotográficos.
            </label>
            <div id="termsBoxP" style="display:none;max-height:150px;overflow-y:auto;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);padding:14px;margin-top:8px;font-family:'Inter',sans-serif;font-size:.72rem;color:rgba(255,255,255,.4);line-height:1.6;">
              Ao contratar este serviço, você concorda com as condições de prestação de serviços fotográficos, incluindo: prazo de entrega de até 15 dias úteis; direitos autorais das imagens pertencem ao fotógrafo (Lei 9.610/98); licença de uso pessoal e profissional concedida ao contratante; arquivos RAW não fazem parte da entrega; política de cancelamento com reembolso integral se comunicado com mais de 7 dias de antecedência, retenção de 50% se menos de 7 dias; não comparecimento sem aviso de 24h configura no-show sem direito a reembolso; dados tratados conforme LGPD (Lei 13.709/18); foro da Comarca de São Paulo/SP.
            </div>
          </div>
          <div style="margin-bottom:20px;">
            <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;font-family:'Inter',sans-serif;font-size:.78rem;color:rgba(255,255,255,.55);line-height:1.5;">
              <input type="checkbox" name="image_usage" style="margin-top:3px;accent-color:#C5A059;min-width:16px;">
              Autorizo o uso das minhas imagens para portfólio e divulgação do fotógrafo.
            </label>
          </div>

          <button type="submit" id="checkoutSubmitBtn" style="width:100%;background:linear-gradient(135deg,#C5A059,#F5E27A,#C5A059);background-size:200%;color:#000;border:none;padding:16px;font-family:'Inter',sans-serif;font-size:.75rem;font-weight:600;letter-spacing:.2em;text-transform:uppercase;cursor:pointer;transition:background-position .4s;">PAGAR COM PIX OU CARTÃO →</button>
          <p id="checkoutError" style="display:none;color:#ff6b6b;font-size:.8rem;text-align:center;margin-top:12px;"></p>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ══ MODAL FALAR ANTES ══════════════════════════════════════════════ -->
<div class="modal fade" id="talkModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
    <div class="modal-content" style="background:#0a0a0a;border:1px solid rgba(255,255,255,.1);border-radius:0;">
      <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,.08);padding:24px 28px 20px;">
        <div>
          <p style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.2em;text-transform:uppercase;color:rgba(255,255,255,.35);margin:0 0 4px;">INTERESSE EM</p>
          <h5 id="talkPkgName" style="font-family:'EB Garamond',Georgia,serif;font-size:1.5rem;color:#fff;margin:0;font-weight:400;"></h5>
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding:28px;">
        <p style="font-family:'EB Garamond',Georgia,serif;font-style:italic;color:rgba(255,255,255,.5);font-size:1rem;margin-bottom:24px;">Deixe seu contato e entro em conversa para responder suas dúvidas antes de qualquer compromisso.</p>
        <form id="talkForm">
          <?= csrf_field() ?>
          <input type="hidden" id="talk_package_id" name="package_id">
          <input type="hidden" id="talk_hero_id" name="hero_id" value="0">
          <div class="mb-3"><input type="text" name="name" required placeholder="Nome completo" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;"></div>
          <div class="mb-3"><input type="email" name="email" required placeholder="E-mail" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;"></div>
          <div class="mb-4"><input type="tel" name="phone" placeholder="WhatsApp / Celular" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;"></div>
          <button type="submit" style="width:100%;background:transparent;border:1px solid rgba(197,160,89,.5);color:#C5A059;padding:14px;font-family:'Inter',sans-serif;font-size:.72rem;font-weight:500;letter-spacing:.18em;text-transform:uppercase;cursor:pointer;">ENVIAR MEU CONTATO</button>
          <p id="talkSuccess" style="display:none;color:#6bcb77;font-size:.85rem;text-align:center;margin-top:16px;"></p>
        </form>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// ── ViaCEP auto-fill ──
function fetchCep(cep, prefix) {
    fetch('https://viacep.com.br/ws/' + cep + '/json/')
        .then(r => r.json())
        .then(d => {
            if (!d.erro) {
                const addr = document.getElementById(prefix + 'Addr');
                const neigh = document.getElementById(prefix + 'Neigh');
                const city = document.getElementById(prefix + 'City');
                const state = document.getElementById(prefix + 'State');
                if (addr && !addr.value) addr.value = d.logradouro || '';
                if (neigh) neigh.value = d.bairro || '';
                if (city) city.value = d.localidade || '';
                if (state) state.value = d.uf || '';
            }
        }).catch(() => {});
}
</script>
<script>
function openCheckout(pkgId, pkgName, price) {
    document.getElementById('checkoutPkgName').textContent  = pkgName;
    document.getElementById('checkoutPkgPrice').textContent = 'R\u00a0' + Number(price).toLocaleString('pt-BR', {minimumFractionDigits:0});
    document.getElementById('chk_package_id').value = pkgId;
    document.getElementById('checkoutError').style.display = 'none';
    new bootstrap.Modal(document.getElementById('checkoutModal')).show();
}

function openTalk(pkgId, pkgName) {
    document.getElementById('talkPkgName').textContent = pkgName;
    document.getElementById('talk_package_id').value   = pkgId;
    document.getElementById('talkSuccess').style.display = 'none';
    new bootstrap.Modal(document.getElementById('talkModal')).show();
}

document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('checkoutSubmitBtn');
    const errEl = document.getElementById('checkoutError');
    btn.textContent = 'PROCESSANDO...';
    btn.disabled = true;
    fetch('<?= base_url('comprar-ensaio') ?>', {
        method: 'POST', body: new FormData(this), headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.checkout_url) {
            window.location.href = data.checkout_url;
        } else {
            errEl.textContent = data.message || 'Erro ao processar. Tente novamente.';
            errEl.style.display = 'block';
            btn.textContent = 'PAGAR COM PIX OU CARTÃO →';
            btn.disabled = false;
        }
    })
    .catch(() => {
        errEl.textContent = 'Erro de conexão. Tente novamente.';
        errEl.style.display = 'block';
        btn.textContent = 'PAGAR COM PIX OU CARTÃO →';
        btn.disabled = false;
    });
});

document.getElementById('talkForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    btn.textContent = 'ENVIANDO...';
    btn.disabled = true;
    fetch('<?= base_url('quero-falar') ?>', {
        method: 'POST', body: new FormData(this), headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        const msg = document.getElementById('talkSuccess');
        msg.textContent = data.message || 'Recebemos seu contato!';
        msg.style.display = 'block';
        this.querySelectorAll('input[type="text"],input[type="email"],input[type="tel"]').forEach(i => i.value = '');
        btn.textContent = 'ENVIAR MEU CONTATO';
        btn.disabled = false;
    })
    .catch(() => { btn.textContent = 'ENVIAR MEU CONTATO'; btn.disabled = false; });
});
</script>
<?= $this->endSection() ?>
