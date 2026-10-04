<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center"><div class="col-xl-9">
<div class="mb-4"><h2 class="text-danger fw-bold mb-1"><?= esc($title) ?></h2><p class="text-muted mb-0">Cole a captura completa da publicação. O sistema separa perfil, links, texto, hashtags e sinais de contexto.</p></div>
<form method="post" action="<?= site_url('admin/threads/prepare') ?>" class="card bg-dark border-danger">
    <div class="card-header">Colagem do Threads</div><div class="card-body row g-3">
        <div class="col-12"><label class="form-label">Cole aqui o texto completo *</label><textarea name="pasted_text" rows="15" class="form-control bg-dark text-white border-secondary" required placeholder="[**usuario**](https://www.threads.com/@usuario)
[5h](https://www.threads.com/@usuario/post/...)
More

Texto publicado pela pessoa...
#hashtag
Like
Reply
Repost
Share
Follow"><?= old('pasted_text') ?></textarea></div>
        <div class="col-12"><div class="alert alert-secondary mb-0"><strong>O sistema identifica:</strong> usuário, perfil, link do post, tempo relativo, texto principal, hashtags, profissão e afinidade estética quando aparecem na colagem.</div></div>
        <div class="col-12 d-flex justify-content-end"><button class="btn btn-danger btn-lg">Preparar abordagem →</button></div>
    </div>
</form>
</div></div>
<?= $this->endSection() ?>
