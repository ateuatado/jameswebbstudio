<?php

namespace Tests\Integration;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Shield\Test\AuthenticationTesting;
use CodeIgniter\Shield\Entities\User;

class AdminLocalTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use AuthenticationTesting;

    protected function setUp(): void
    {
        parent::setUp();
        $db = db_connect();
        if ($db->database !== 'jws_validation_only' || (int) $db->port !== 33317) {
            throw new \RuntimeException('Disposable MySQL instance required.');
        }
        \Config\Services::injectMock('response', new \CodeIgniter\HTTP\Response(config('App')));
    }

    public function testAdminCanOpenDashboardAndCreateCategory(): void
    {
        $suffix = bin2hex(random_bytes(5));
        $users = model(\CodeIgniter\Shield\Models\UserModel::class);
        $user = new User(['username' => 'validation-' . $suffix, 'email' => $suffix . '@example.invalid', 'password' => 'LocalTest-123456!']);
        $users->save($user);
        $user = $users->findById($users->getInsertID());
        $user->addGroup('admin');
        $this->actingAs($user);
        $dashboard = $this->get('/admin');
        $dashboard->assertStatus(200);
        $this->assertSame('noindex, follow', $dashboard->response()->getHeaderLine('X-Robots-Tag'));
        $this->actingAs($user);
        $this->post('/admin/categories', ['name' => 'Validação ' . $suffix, 'slug' => 'validacao-' . $suffix, 'is_active' => 1])->assertRedirect();
        $this->assertNotNull((new \App\Models\CategoryModel())->where('slug', 'validacao-' . $suffix)->first());
    }
}
