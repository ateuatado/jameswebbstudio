<?php

namespace Tests\Controllers;

use CodeIgniter\Test\CIUnitTestCase;

class HeroPageTest extends CIUnitTestCase
{
    use \CodeIgniter\Test\FeatureTestTrait;
    use \CodeIgniter\Test\DatabaseTestTrait;

    protected $refresh   = true;
    protected $migrate   = true;
    protected $namespace = 'App';

    public function testHeroPageLoadsCorrectly(): void
    {
        $model = new \App\Models\Hero();
        $model->insert([
            'name'  => 'Fake Athlete',
            'sport' => 'Test Runner',
            'slug'  => 'fake-athlete',
        ]);

        $result = $this->get('/fake-athlete');

        $result->assertStatus(200);
        $result->assertSee('Fake Athlete');
        $result->assertSee('Test Runner');
    }

    public function testHeroPageThrows404ForUnknownSlug(): void
    {
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);
        $this->get('/unknown-hero-1234');
    }
}
