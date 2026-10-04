<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center"><div class="col-xl-8">
<div class="mb-4"><h2 class="text-danger fw-bold mb-1"><?= esc($title) ?></h2><p class="text-muted mb-0">Cole os dados da publicação. A próxima tela preparará os textos para sua revisão.</p></div>
<form method="post" action="<?= site_url('admin/threads/prepare') ?>" class="card bg-dark border-danger">
    <div class="card-header">Dados da publicação</div><div class="card-body row g-3">
        <div class="col-md-6"><label class="form-label">Perfil do Threads *</label><input name="threads_username" class="form-control bg-dark text-white border-secondary" required value="<?= old('threads_username') ?>" placeholder="@eliziemota_"></div>
        <div class="col-md-6"><label class="form-label">Link da publicação <span class="text-muted">(opcional)</span></label><input type="url" name="threads_post_url" class="form-control bg-dark text-white border-secondary" value="<?= old('threads_post_url') ?>" placeholder="https://www.threads.com/..."></div>
        <div class="col-12"><label class="form-label">Texto da postagem *</label><textarea name="original_text" rows="7" class="form-control bg-dark text-white border-secondary" required placeholder="Uso a minha liberdade para ser quem eu quiser..."><?= old('original_text') ?></textarea></div>
        <div class="col-12 d-flex justify-content-end"><button class="btn btn-danger btn-lg">Preparar abordagem →</button></div>
    </div>
</form>
</div></div>
<?= $this->endSection() ?>
