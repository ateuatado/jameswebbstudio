<?php

namespace Tests\Controllers;

use App\Libraries\ServiceContent\{Content, Repository, Provider, Worker};
use Tests\Support\LocalWebTestCase;

class ServiceContentTest extends LocalWebTestCase
{
    use \CodeIgniter\Shield\Test\AuthenticationTesting;
    private Repository $repo;
    private int $family;

    protected function setUp(): void
    {
        parent::setUp();
        require_once APPPATH . 'Database/Migrations/2026-10-23-130000_CreateServiceContent.php';
        $migration = new \App\Database\Migrations\CreateServiceContent(\Config\Database::forge());
        $migration->down(); $migration->up();
        require_once APPPATH . 'Database/Migrations/2026-10-23-140000_CreateServiceContextDrafts.php';
        $contextMigration = new \App\Database\Migrations\CreateServiceContextDrafts(\Config\Database::forge());
        $contextMigration->down(); $contextMigration->up();
        $this->repo = new Repository();
        $this->repo->saveContext('O estúdio oferece fotografia de produtos. O atendimento depende de briefing. Não há preços públicos nesta modalidade.', '5511999999999', 1);
        $this->repo->createFamily('Fotografia de produtos', 'Fotografias para apresentar produtos e suas características.', 1);
        $this->family = (int) $this->repo->table('families')->get()->getRowArray()['id'];
        config('ServicePages')->enabled = true;
    }

    protected function tearDown(): void
    {
        config('ServicePages')->enabled = false;
        parent::tearDown();
    }

    private function content(string $title = 'Fotografia de joias'): array
    {
        return ['title' => $title, 'h1' => $title, 'introduction' => 'Fotografia de joias para apresentar cada detalhe do produto.', 'meta_description' => 'Conheça a fotografia de joias e solicite um orçamento para o seu projeto.', 'whatsapp_message' => 'Olá! Gostaria de fotografar minhas joias.', 'sections' => [['heading' => 'Como funciona', 'body' => 'Conversamos sobre as peças e os objetivos para preparar o projeto.']], 'faq' => [], 'photo_ids' => [], 'fact_warnings' => []];
    }

    private function draft(): int
    {
        $id = $this->repo->createPage($this->family, 'Fotografia de joias', 1);
        $this->repo->edit($id, 0, $this->content(), 1);
        return $id;
    }

    private function administrator(string $group = 'admin'): void
    {
        if (! db_connect()->tableExists('users')) {
            require_once ROOTPATH . 'vendor/codeigniter4/shield/src/Database/Migrations/2020-12-28-223112_create_auth_tables.php';
            (new \CodeIgniter\Shield\Database\Migrations\CreateAuthTables(\Config\Database::forge()))->up();
        }
        $users = model(\CodeIgniter\Shield\Models\UserModel::class);
        $suffix = bin2hex(random_bytes(5));
        $user = new \CodeIgniter\Shield\Entities\User(['username' => 'editor-' . $suffix, 'email' => $suffix . '@example.invalid', 'password' => 'LocalTest-123456!', 'active' => 1]);
        $users->save($user); $user = $users->findById($users->getInsertID()); $user->addGroup($group);
        $this->actingAs($user);
    }

    public function testAdminEditorAndPreview(): void
    {
        $id = $this->draft(); $this->administrator();
        $this->get('/admin/paginas-servicos')->assertStatus(200);
        $this->get('/admin/paginas-servicos/' . $id)->assertStatus(200);
        $preview = $this->get('/admin/paginas-servicos/' . $id . '/previa');
        $preview->assertStatus(200);
        $this->assertStringContainsString('private', $preview->response()->getHeaderLine('Cache-Control'));
        $this->assertStringContainsString('no-store', $preview->response()->getHeaderLine('Cache-Control'));
        $preview->assertSee('noindex, follow');
    }

    public function testAiFillsSeoAndContactFieldsWithoutManualForm(): void
    {
        $id = $this->repo->createPage($this->family, 'Ensaio de Dia das Mães', 1);
        $this->administrator();
        $editor = $this->get('/admin/paginas-servicos/' . $id);
        $editor->assertSee('Criar página completa com IA');
        $editor->assertDontSee('id="manual-editor"');
        $content = $this->content();
        $content['keywords'] = ['ensaio de dia das mães', 'fotografia de mães e filhos'];
        $content['search_intent'] = 'Encontrar um ensaio para celebrar o Dia das Mães.';
        $content['cta_label'] = 'Conversar sobre meu ensaio';
        $provider = new class($content) implements Provider {
            public function __construct(private array $data) {}
            public function generate(string $topic, string $family, string $facts): array {
                return ['content' => $this->data, 'input_tokens' => 200, 'output_tokens' => 400, 'model' => 'fake'];
            }
        };
        $this->repo->batch($this->family, ['Ensaio de Dia das Mães'], bin2hex(random_bytes(16)), 1, true, $id);
        (new Worker($this->repo, $provider))->once();
        $revision = $this->repo->revision((int) $this->repo->page($id)['current_revision_id']);
        $this->assertSame($content['keywords'], $revision['data']['keywords']);
        $result = $this->get('/admin/paginas-servicos/' . $id);
        $result->assertSee('Página pronta para revisão');
        $result->assertSee('fotografia de mães e filhos');
        $this->repo->publish($id, 1, 1);
        $public = $this->get('/servicos/fotografia-de-produtos/ensaio-de-dia-das-maes');
        $public->assertStatus(200); $public->assertSee('Conversar sobre meu ensaio');
        $public->assertDontSee('name="keywords"');
    }

    public function testAdminMutationRejectsMissingCsrf(): void
    {
        $this->administrator();
        $this->expectException(\CodeIgniter\Security\Exceptions\SecurityException::class);
        $this->post('/admin/paginas-servicos/familia', ['name' => 'Moda', 'description' => 'Fotografia de moda.']);
    }

    public function testOrdinaryUserCannotOpenEditor(): void
    {
        $this->administrator('user');
        $this->get('/admin/paginas-servicos')->assertRedirect();
    }

    public function testAdminMutationAcceptsCsrf(): void
    {
        $this->administrator();
        $this->get('/admin/paginas-servicos');
        $hash = csrf_hash();
        $result = $this->withSession(session()->get())->post('/admin/paginas-servicos/familia', [csrf_token() => $hash, 'name' => 'Moda', 'description' => 'Fotografia de moda.']);
        $result->assertRedirect();
        $this->assertSame(1, $this->repo->table('families')->where('slug', 'moda')->countAllResults());
    }

    public function testSaveAutomaticallyFillsMissingSectionsAndSeoWithoutLosingText(): void
    {
        $id = $this->draft(); $this->administrator();
        $generated = $this->content();
        $generated['sections'][] = ['heading' => 'Como pedir orçamento', 'body' => 'Converse sobre o projeto pelo WhatsApp.'];
        $generated['keywords'] = ['fotografia de joias', 'fotos de produtos'];
        $generated['search_intent'] = 'Contratar fotografia de joias.';
        $provider = new class($generated) implements Provider {
            public function __construct(private array $data) {}
            public function generate(string $topic, string $family, string $facts): array {
                return ['content' => $this->data, 'input_tokens' => 100, 'output_tokens' => 300, 'model' => 'fake'];
            }
        };
        \Config\Services::injectMock('serviceContentProvider', $provider);
        $previous = config('ServicePages')->generationEnabled;
        config('ServicePages')->generationEnabled = true;
        try {
            $this->get('/admin/paginas-servicos/' . $id);
            $data = $this->content('Título que escrevi');
            $data['version'] = 1; $data['sections'] = []; $data['keyword_list'] = ''; $data['search_intent'] = '';
            $data[csrf_token()] = csrf_hash();
            $this->withSession(session()->get())->post('/admin/paginas-servicos/' . $id . '/salvar', $data)->assertRedirect();
            $revision = $this->repo->revision((int) $this->repo->page($id)['current_revision_id']);
            $this->assertSame('Título que escrevi', $revision['data']['title']);
            $this->assertCount(2, $revision['data']['sections']);
            $this->assertSame($generated['keywords'], $revision['data']['keywords']);
            $this->assertSame($generated['search_intent'], $revision['data']['search_intent']);
            $this->assertSame(0, (int) $this->repo->page($id)['published_revision_id']);
        } finally {
            config('ServicePages')->generationEnabled = $previous;
            \Config\Services::resetSingle('serviceContentProvider');
        }
    }

    public function testPublishedSnapshotSurvivesEditingAndSitemapExcludesDrafts(): void
    {
        $id = $this->draft();
        $this->repo->publish($id, 1, 1);
        $this->repo->edit($id, 2, $this->content('Novo rascunho'), 1);
        $result = $this->get('/servicos/fotografia-de-produtos/fotografia-de-joias');
        $result->assertStatus(200); $result->assertSee('Fotografia de joias'); $result->assertDontSee('Novo rascunho');
        $result->assertSee('https://wa.me/5511999999999');
        $this->get('/sitemap.xml')->assertSee('/servicos/fotografia-de-produtos/fotografia-de-joias');
        $this->repo->archive($id, 3, 1);
        $this->get('/servicos/fotografia-de-produtos/fotografia-de-joias')->assertStatus(410);
        $this->get('/sitemap.xml')->assertDontSee('/servicos/fotografia-de-produtos/fotografia-de-joias');
    }

    public function testDraftNotPublicAndAdminProtected(): void
    {
        auth()->logout();
        $this->draft();
        $this->get('/admin/paginas-servicos')->assertRedirect();
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);
        $this->get('/servicos/fotografia-de-produtos/fotografia-de-joias');
    }

    public function testConflictDoesNotCreateRevision(): void
    {
        $id = $this->draft();
        $before = $this->repo->table('revisions')->countAllResults();
        try { $this->repo->edit($id, 0, $this->content('Conflito'), 1); $this->fail('Expected conflict'); }
        catch (\RuntimeException $error) { $this->assertStringContainsString('mudou', $error->getMessage()); }
        $this->assertSame($before, $this->repo->table('revisions')->countAllResults());
    }

    public function testRedirectAndRestorationStayConsistent(): void
    {
        $id = $this->draft(); $revision = (int) $this->repo->page($id)['current_revision_id'];
        $this->repo->publish($id, 1, 1);
        $this->repo->rename($id, 2, 'Joias para catálogo', 1);
        $this->get('/servicos/fotografia-de-produtos/fotografia-de-joias')->assertRedirectTo(site_url('servicos/fotografia-de-produtos/joias-para-catalogo'));
        $this->repo->edit($id, 3, [], 1, $revision);
        $this->assertNotSame($revision, (int) $this->repo->page($id)['current_revision_id']);
        $this->assertSame($revision, (int) $this->repo->page($id)['published_revision_id']);
    }

    public function testUnresolvedWarningsBlockPublication(): void
    {
        $id = $this->draft(); $content = $this->content(); $content['fact_warnings'] = ['Confirmar prazo.'];
        $this->repo->edit($id, 1, $content, 1);
        $this->expectException(\RuntimeException::class);
        $this->repo->publish($id, 2, 1);
    }

    public function testBatchDeduplicationIdempotenceAndWorkerNeverPublishes(): void
    {
        $key = bin2hex(random_bytes(16));
        $batch = $this->repo->batch($this->family, ['Joias', 'Joias', 'Alimentos'], $key, 1, true);
        $this->assertSame($batch, $this->repo->batch($this->family, ['Joias'], $key, 1, true));
        $this->assertSame(2, $this->repo->table('jobs')->countAllResults());
        $provider = $this->fakeProvider();
        $worker = new Worker($this->repo, $provider);
        $this->assertTrue($worker->once()); $this->assertTrue($worker->once()); $this->assertFalse($worker->once());
        $this->assertCount(0, $this->repo->publicPages());
        $this->assertSame(2, $this->repo->table('jobs')->where('state', 'succeeded')->countAllResults());
    }

    private function fakeProvider(bool $fail = false): Provider
    {
        $content = $this->content();
        return new class($content, $fail) implements Provider {
            public function __construct(private array $content, private bool $fail) {}
            public function generate(string $topic, string $family, string $facts): array {
                if ($this->fail) throw new \RuntimeException('Fornecedor indisponível.');
                return ['content' => $this->content, 'input_tokens' => 200, 'output_tokens' => 400, 'model' => 'fake'];
            }
        };
    }

    public function testProviderFailureAndCancellationDoNotPublish(): void
    {
        $batch = $this->repo->batch($this->family, ['Joias'], bin2hex(random_bytes(16)), 1, true);
        (new Worker($this->repo, $this->fakeProvider(true)))->once();
        $this->assertSame('failed', $this->repo->table('jobs')->get()->getRowArray()['state']);
        $this->assertSame(0, $this->repo->table('revisions')->countAllResults());
        $this->repo->table('batches')->where('id', $batch)->update(['status' => 'cancelled']);
        $this->assertCount(0, $this->repo->publicPages());
    }

    public function testBudgetPreventsProviderCall(): void
    {
        $this->repo->batch($this->family, ['Joias'], bin2hex(random_bytes(16)), 1, true);
        $old = config('ServicePages')->dailyTokenLimit;
        config('ServicePages')->dailyTokenLimit = 0;
        try { (new Worker($this->repo, $this->fakeProvider()))->once(); }
        finally { config('ServicePages')->dailyTokenLimit = $old; }
        $this->assertSame('failed', $this->repo->table('jobs')->get()->getRowArray()['state']);
        $this->assertSame(0, $this->repo->table('revisions')->countAllResults());
    }

    public function testSuccessfulUsageReplacesWorstCaseReservation(): void
    {
        $previous = config('ServicePages')->dailyTokenLimit;
        config('ServicePages')->dailyTokenLimit = 21000;
        try {
            $batch = $this->repo->batch($this->family, ['Joias', 'Alimentos'], bin2hex(random_bytes(16)), 1, true);
            $worker = new Worker($this->repo, $this->fakeProvider());
            $worker->once(); $worker->once();
            $this->assertSame(2, $this->repo->table('jobs')->where('state', 'succeeded')->countAllResults());
            $budget = $this->repo->table('budgets')->where('budget_day', date('Y-m-d'))->get()->getRowArray();
            $this->assertSame(1200, (int) $budget['reserved_tokens']);
            $this->assertSame(1080, (int) $budget['reserved_microusd']);
            $row = $this->repo->table('batches')->where('id', $batch)->get()->getRowArray();
            $this->assertSame(1200, (int) $row['reserved_tokens']);
            $this->assertSame(1200, (int) $row['used_tokens']);
        } finally { config('ServicePages')->dailyTokenLimit = $previous; }
    }

    public function testPanelProcessesOnlySelectedBatchAndUpdatesProgress(): void
    {
        $this->administrator();
        $first = $this->repo->batch($this->family, ['Joias A'], bin2hex(random_bytes(16)), 1, true);
        $second = $this->repo->batch($this->family, ['Joias B', 'Joias C'], bin2hex(random_bytes(16)), 1, true);
        $old = config('ServicePages')->generationEnabled;
        config('ServicePages')->generationEnabled = true;
        \Config\Services::injectMock('serviceContentProvider', $this->fakeProvider());
        try {
            $this->get('/admin/paginas-servicos');
            $response = $this->withSession(session()->get())->post('/admin/paginas-servicos/lotes/' . $second . '/processar', [csrf_token() => csrf_hash()]);
            $response->assertStatus(200);
            $data = json_decode($response->response()->getBody(), true);
            $this->assertTrue($data['processed']);
            $this->assertNull($data['error']);
            $this->assertSame(1, $data['summary']['done']);
            $this->assertSame(1, $data['summary']['pending']);
            $this->assertSame(1, $this->repo->table('jobs')->where(['batch_id' => $first, 'state' => 'queued'])->countAllResults());
            $this->assertCount(0, $this->repo->publicPages());
        } finally {
            config('ServicePages')->generationEnabled = $old;
            \Config\Services::resetSingle('serviceContentProvider');
        }
    }

    public function testBatchSummaryDoesNotKeepFinishedJobsQueued(): void
    {
        $batch = $this->repo->batch($this->family, ['Joias'], bin2hex(random_bytes(16)), 1, true);
        $this->repo->table('jobs')->where('batch_id', $batch)->update(['state' => 'failed']);
        $summary = $this->repo->batches()[0];
        $this->assertSame('failed', $summary['status']);
        $this->assertSame(0, $summary['pending']);
        $this->assertSame(1, $summary['failed']);
    }

    public function testHtmlIsRejected(): void
    {
        $content = $this->content(); $content['introduction'] = '<script>alert(1)</script>';
        $this->expectException(\InvalidArgumentException::class); Content::validate($content);
    }

    public function testGenerationDoesNotOverwriteConcurrentEditor(): void
    {
        $id = $this->draft();
        $this->repo->batch($this->family, ['Fotografia de joias'], bin2hex(random_bytes(16)), 1, true, $id);
        $this->repo->edit($id, 1, $this->content('Edição humana'), 1);
        $humanRevision = $this->repo->page($id)['current_revision_id'];
        (new Worker($this->repo, $this->fakeProvider()))->once();
        $this->assertSame($humanRevision, $this->repo->page($id)['current_revision_id']);
        $this->assertSame(3, $this->repo->table('revisions')->countAllResults());
    }

    public function testCancelledJobNeverCallsProvider(): void
    {
        $batch = $this->repo->batch($this->family, ['Joias'], bin2hex(random_bytes(16)), 1, true);
        $this->repo->table('batches')->where('id', $batch)->update(['status' => 'cancelled']);
        (new Worker($this->repo, $this->fakeProvider(true)))->once();
        $job = $this->repo->table('jobs')->get()->getRowArray();
        $this->assertSame('cancelled', $job['state']); $this->assertSame(0, (int) $job['attempts']);
    }

    public function testAiCanProposeContextWithoutManualFactsOrPublication(): void
    {
        $this->repo->table('contexts')->emptyTable();
        $key = bin2hex(random_bytes(16));
        $batch = $this->repo->suggestContext($key, 1);
        $this->assertSame($batch, $this->repo->suggestContext($key, 1));
        $this->assertTrue((new Worker($this->repo, $this->fakeProvider()))->once());
        $draft = $this->repo->table('context_drafts')->get()->getRowArray();
        $this->assertNotEmpty($draft['facts']);
        $this->assertSame(0, (int) $draft['approved_context_id']);
        $this->assertNull($this->repo->context());
        $this->assertSame(0, $this->repo->table('pages')->countAllResults());
        $this->assertSame(0, $this->repo->table('revisions')->countAllResults());
    }

    public function testExpiredLeaseIsNotAutomaticallyBilledAgain(): void
    {
        $this->repo->batch($this->family, ['Joias'], bin2hex(random_bytes(16)), 1, true);
        $this->repo->table('jobs')->update(['state' => 'running', 'lease_expires_at' => '2020-01-01 00:00:00', 'attempts' => 1]);
        $this->assertFalse((new Worker($this->repo, $this->fakeProvider()))->once());
        $job = $this->repo->table('jobs')->get()->getRowArray();
        $this->assertSame('failed', $job['state']); $this->assertSame(1, (int) $job['attempts']);
        $this->assertSame(0, $this->repo->table('revisions')->countAllResults());
    }
}
