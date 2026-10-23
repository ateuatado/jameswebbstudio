<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\ServiceContent\{Content, Repository};
use CodeIgniter\Exceptions\PageNotFoundException;

class ServicePagesController extends BaseController
{
    private function repo(): Repository
    {
        $repo = new Repository();
        if (! $repo->ready()) throw PageNotFoundException::forPageNotFound('Instale as migrations do módulo.');
        return $repo;
    }

    private function permit(string $action): void
    {
        if (! auth()->user()->can('service-pages.' . $action)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    private function actor(): int { return (int) auth()->id(); }

    private function execute(callable $action, string $return = 'admin/paginas-servicos')
    {
        try { $action(); return redirect()->to(site_url($return))->with('message', 'Operação concluída.'); }
        catch (\InvalidArgumentException | \RuntimeException $error) { return redirect()->to(site_url($return))->with('error', esc($error->getMessage())); }
    }

    public function index(?array $preview = null)
    {
        $this->permit('read');
        $repo = new Repository();
        if (! $repo->ready()) return view('admin/service_pages/setup', ['title' => 'Páginas de serviços']);
        $pageNumber = max(1, min(100000, (int) $this->request->getGet('pagina')));
        $suggestion = null;
        if ($repo->db->tableExists('service_content_context_drafts')) {
            $suggestion = $repo->db->table('service_content_context_drafts d')->select('d.*')->join('service_content_batches b', 'b.id = d.batch_id')->where('d.approved_context_id', 0)->where('d.facts IS NOT NULL', null, false)->where('b.status !=', 'cancelled')->orderBy('d.id', 'desc')->get(1)->getRowArray();
        }
        return view('admin/service_pages/index', [
            'title' => 'Páginas de serviços', 'context' => $repo->context(),
            'families' => $repo->table('families')->orderBy('name')->get()->getResultArray(),
            'pages' => $repo->table('pages')->orderBy('id', 'desc')->get(50, ($pageNumber - 1) * 50)->getResultArray(),
            'pageNumber' => $pageNumber, 'totalPages' => $repo->table('pages')->countAllResults(),
            'jobs' => $repo->table('jobs')->orderBy('id', 'desc')->get(50)->getResultArray(),
            'batches' => $repo->table('batches')->orderBy('id', 'desc')->get(20)->getResultArray(),
            'preview' => $preview, 'configuration' => config('ServicePages'),
            'suggestion' => $suggestion,
            'contextKey' => bin2hex(random_bytes(16)),
        ]);
    }

    public function context()
    {
        $this->permit('edit');
        return $this->execute(function () {
            $r = $this->repo();
            if ($this->request->getPost('suggestion_id') && $this->request->getPost('approved') !== '1') throw new \RuntimeException('Confirme a aprovação da proposta.');
            $r->saveContext((string) $this->request->getPost('facts'), preg_replace('/\D/', '', (string) $this->request->getPost('whatsapp')), $this->actor());
            if ($this->request->getPost('suggestion_id') && $r->db->tableExists('service_content_context_drafts')) {
                $r->table('context_drafts')->where('id', (int) $this->request->getPost('suggestion_id'))->update(['approved_context_id' => $r->context()['id']]);
            }
        });
    }

    public function family()
    {
        $this->permit('edit');
        return $this->execute(fn () => $this->repo()->createFamily((string) $this->request->getPost('name'), (string) $this->request->getPost('description'), $this->actor()));
    }

    public function suggestContext()
    {
        $this->permit('generate');
        return $this->execute(function () {
            if (! config('ServicePages')->generationEnabled) throw new \RuntimeException('Geração desativada no ambiente.');
            $this->repo()->suggestContext((string) $this->request->getPost('key'), $this->actor());
        });
    }

    public function prepare()
    {
        $this->permit('edit');
        $family = (int) $this->request->getPost('family_id');
        try {
            $repo = $this->repo(); $repo->family($family);
            $raw = (string) $this->request->getPost('topics');
            if (strlen($raw) > 10000) throw new \RuntimeException('Lista longa demais.');
            $topics = array_values(array_filter(array_map('trim', preg_split('/\R/', $raw))));
            if (! $topics || count($topics) > config('ServicePages')->maxBatch) throw new \RuntimeException('Informe entre 1 e 25 temas.');
            $seen = []; $rows = [];
            foreach ($topics as $topic) {
                $slug = Content::slug(Content::text($topic, 200, 'tema'));
                $exists = isset($seen[$slug]) || $repo->table('pages')->where(['family_id' => $family, 'slug' => $slug])->countAllResults();
                $rows[] = ['topic' => $topic, 'slug' => $slug, 'duplicate' => $exists]; $seen[$slug] = true;
            }
            return $this->index(['family_id' => $family, 'topics' => $topics, 'rows' => $rows, 'key' => bin2hex(random_bytes(16))]);
        } catch (\InvalidArgumentException | \RuntimeException $error) {
            return redirect()->to(site_url('admin/paginas-servicos'))->with('error', esc($error->getMessage()));
        }
    }

    public function batch()
    {
        $this->permit('edit');
        $generate = $this->request->getPost('generate') === '1';
        if ($generate) $this->permit('generate');
        if ($generate && ! config('ServicePages')->generationEnabled) return redirect()->back()->with('error', 'Geração desativada no ambiente.');
        return $this->execute(fn () => $this->repo()->batch((int) $this->request->getPost('family_id'), (array) $this->request->getPost('topics'), (string) $this->request->getPost('key'), $this->actor(), $generate));
    }

    public function edit(int $id)
    {
        $this->permit('read');
        $repo = $this->repo(); $page = $repo->page($id);
        $revision = $repo->revision((int) $page['current_revision_id']);
        $default = ['title' => $page['topic'], 'h1' => $page['topic'], 'introduction' => '', 'meta_description' => '', 'whatsapp_message' => 'Olá! Gostaria de um orçamento para ' . $page['topic'] . '.', 'sections' => [], 'faq' => [], 'photo_ids' => [], 'fact_warnings' => []];
        $content = array_replace(['keywords' => [], 'search_intent' => '', 'cta_label' => 'Solicitar orçamento pelo WhatsApp'], $revision['data'] ?? $default);
        return view('admin/service_pages/edit', ['title' => 'Editar página', 'page' => $page, 'content' => $content,
            'generation' => $repo->table('jobs')->where('page_id', $id)->orderBy('id', 'desc')->get(1)->getRowArray(),
            'hasContext' => $repo->context() !== null, 'aiEnabled' => config('ServicePages')->generationEnabled,
            'revisions' => $repo->table('revisions')->where('page_id', $id)->orderBy('id', 'desc')->get(20)->getResultArray(),
            'photos' => $repo->db->table('photos')->select('photos.id, heroes.name, photos.caption')->join('heroes', 'heroes.id = photos.hero_id')->where('heroes.published', 1)->orderBy('photos.id', 'desc')->get(200)->getResultArray(),
            'path' => $repo->path($page), 'key' => bin2hex(random_bytes(16))]);
    }

    public function save(int $id)
    {
        $this->permit('edit');
        return $this->execute(function () use ($id) {
            $data = $this->request->getPost();
            $sections = [];
            foreach ((array) ($data['sections'] ?? []) as $row) if (trim($row['heading'] ?? '') || trim($row['body'] ?? '')) $sections[] = $row;
            $faqs = [];
            foreach ((array) ($data['faq'] ?? []) as $row) if (trim($row['question'] ?? '') || trim($row['answer'] ?? '')) $faqs[] = $row;
            $data['sections'] = $sections; $data['faq'] = $faqs;
            $data['keywords'] = array_values(array_filter(array_map('trim', preg_split('/[,\n\r]+/', (string) ($data['keyword_list'] ?? '')))));
            $data['fact_warnings'] = array_values(array_filter(array_map('trim', preg_split('/\R/', (string) ($data['warnings'] ?? '')))));
            $r = $this->repo();
            $version = (int) $data['version'];
            if (! $sections || ! $data['keywords'] || trim((string) ($data['search_intent'] ?? '')) === '') {
                $this->permit('generate');
                if ((int) $r->page($id)['lock_version'] !== $version) throw new \RuntimeException('A página mudou. Recarregue antes de completar.');
                $generated = $this->generateNow($id, bin2hex(random_bytes(16)));
                foreach (['title', 'h1', 'introduction', 'meta_description', 'whatsapp_message', 'search_intent', 'cta_label'] as $field) {
                    if (trim((string) ($data[$field] ?? '')) === '') $data[$field] = $generated[$field];
                }
                if (! $data['sections']) $data['sections'] = $generated['sections'];
                if (! $data['keywords']) $data['keywords'] = $generated['keywords'];
                if (! $data['faq']) $data['faq'] = $generated['faq'];
                $data['fact_warnings'] = array_values(array_unique(array_merge($data['fact_warnings'], $generated['fact_warnings'])));
                $version++;
            }
            $r->edit($id, $version, $data, $this->actor());
        }, 'admin/paginas-servicos/' . $id);
    }

    public function publish(int $id)
    {
        $this->permit('publish');
        return $this->execute(function () use ($id) {
            if ($this->request->getPost('reviewed') !== '1') throw new \RuntimeException('Confirme a revisão dos fatos, fotos e oferta.');
            $this->repo()->publish($id, (int) $this->request->getPost('version'), $this->actor());
        }, 'admin/paginas-servicos/' . $id);
    }

    public function archive(int $id)
    {
        $this->permit('publish');
        return $this->execute(fn () => $this->repo()->archive($id, (int) $this->request->getPost('version'), $this->actor()), 'admin/paginas-servicos/' . $id);
    }

    public function restore(int $id)
    {
        $this->permit('edit');
        return $this->execute(fn () => $this->repo()->edit($id, (int) $this->request->getPost('version'), [], $this->actor(), (int) $this->request->getPost('revision_id')), 'admin/paginas-servicos/' . $id);
    }

    public function generate(int $id)
    {
        $this->permit('generate');
        return $this->execute(fn () => $this->generateNow($id, (string) $this->request->getPost('key')), 'admin/paginas-servicos/' . $id);
    }

    private function generateNow(int $id, string $key): array
    {
        if (! config('ServicePages')->generationEnabled) throw new \RuntimeException('Geração desativada.');
        $r = $this->repo(); $page = $r->page($id);
        $pending = $r->table('jobs')->where('page_id', $id)->whereIn('state', ['queued', 'running'])->orderBy('id')->get(1)->getRowArray();
        if ($pending && $pending['state'] === 'running') throw new \RuntimeException('A IA já está processando esta página. Atualize em alguns instantes.');
        if (! $pending) $r->batch((int) $page['family_id'], [$page['topic']], $key, $this->actor(), true, $id);
        (new \App\Libraries\ServiceContent\Worker($r, service('serviceContentProvider')))->once($id);
        $updated = $r->page($id);
        $job = $r->table('jobs')->where('page_id', $id)->orderBy('id', 'desc')->get(1)->getRowArray();
        if ((int) $updated['lock_version'] !== (int) $page['lock_version'] + 1 || (int) $updated['current_revision_id'] !== (int) ($job['revision_id'] ?? 0)) {
            throw new \RuntimeException($job['error'] ?? 'Não foi possível aplicar o resultado agora. Confira a fila e o histórico.');
        }
        $revision = $r->revision((int) $updated['current_revision_id']);
        return $revision['data'];
    }

    public function cancel(int $id)
    {
        $this->permit('generate');
        return $this->execute(function () use ($id) {
            $r = $this->repo();
            $r->table('batches')->where('id', $id)->update(['status' => 'cancelled']);
            $r->table('jobs')->where(['batch_id' => $id, 'state' => 'queued'])->update(['state' => 'cancelled']);
            $r->audit(0, 'batch_cancelled', $this->actor());
        });
    }

    public function rename(int $id)
    {
        $this->permit('publish');
        return $this->execute(fn () => $this->repo()->rename($id, (int) $this->request->getPost('version'), (string) $this->request->getPost('slug'), $this->actor()), 'admin/paginas-servicos/' . $id);
    }

    public function publishBatch()
    {
        $this->permit('publish');
        return $this->execute(function () {
            if ($this->request->getPost('reviewed') !== '1') throw new \RuntimeException('Confirme a revisão de cada página selecionada.');
            $selected = (array) $this->request->getPost('pages');
            if (! $selected || count($selected) > 25) throw new \RuntimeException('Selecione de 1 a 25 páginas revisadas.');
            // Each page has its own acceptance/version check; report partial success explicitly.
            $failed = [];
            foreach ($selected as $id => $version) {
                try { $this->repo()->publish((int) $id, (int) $version, $this->actor()); }
                catch (\Throwable $error) { $failed[] = (int) $id; }
            }
            if ($failed) throw new \RuntimeException('Publicação parcial: páginas ' . implode(', ', $failed) . ' precisam de revisão. As demais selecionadas foram publicadas.');
        });
    }

    public function retry(int $id)
    {
        $this->permit('generate');
        return $this->execute(function () use ($id) {
            if ($this->request->getPost('confirm_cost') !== '1') throw new \RuntimeException('Confirme que repetir pode gerar novo consumo.');
            $r = $this->repo();
            $job = $r->table('jobs')->where('id', $id)->get()->getRowArray();
            $batch = $job ? $r->table('batches')->where('id', $job['batch_id'])->get()->getRowArray() : null;
            if (! $job || ! $batch || $batch['status'] === 'cancelled' || $job['state'] !== 'failed' || $job['attempts'] >= config('ServicePages')->maxAttempts) throw new \RuntimeException('Item não pode ser repetido.');
            $r->table('jobs')->where(['id' => $id, 'state' => 'failed'])->update(['state' => 'queued', 'error' => null, 'available_at' => date('Y-m-d H:i:s', time() + 30)]);
            $r->table('batches')->where('id', $batch['id'])->where('status !=', 'cancelled')->update(['status' => 'queued']);
            $r->audit((int) $job['page_id'], 'generation_retry', $this->actor());
        });
    }

    public function preview(int $id)
    {
        $this->permit('read');
        $r = $this->repo(); $page = $r->page($id); $revision = $r->revision((int) $page['current_revision_id']);
        if (! $revision) return redirect()->to(site_url('admin/paginas-servicos/' . $id))->with('error', 'Salve um rascunho primeiro.');
        $this->response->setHeader('Cache-Control', 'private, no-store')->setHeader('X-Robots-Tag', 'noindex, follow');
        return view('service_pages/show', \App\Controllers\ServicePages::viewData($r, $page, $revision, true));
    }
}
