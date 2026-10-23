<?php

namespace Tests\Models;

use CodeIgniter\Test\CIUnitTestCase;

class HeroModelTest extends CIUnitTestCase
{
    use \CodeIgniter\Test\DatabaseTestTrait;

    protected $refresh   = false;
    protected $migrate   = true;
    protected $namespace = 'App';

    public function testInsertAndRetrieveHero(): void
    {
        $model = new \App\Models\Hero();
        $testSlug = 'test-hero-' . bin2hex(random_bytes(5));
        
        $heroId = $model->insert([
            'name'  => 'Test Hero',
            'sport' => 'Testing',
            'slug'  => $testSlug,
        ]);

        $this->assertIsInt($heroId);
        $this->assertGreaterThan(0, $heroId);

        $saved = $model->find($heroId);
        $this->assertEquals('Test Hero', $saved['name']);
        $this->assertEquals($testSlug, $saved['slug']);
    }
}
