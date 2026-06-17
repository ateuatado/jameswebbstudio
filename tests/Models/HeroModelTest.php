<?php

namespace Tests\Models;

use CodeIgniter\Test\CIUnitTestCase;

class HeroModelTest extends CIUnitTestCase
{
    use \CodeIgniter\Test\DatabaseTestTrait;

    protected $refresh   = true;
    protected $migrate   = true;
    protected $namespace = 'App';

    public function testInsertAndRetrieveHero(): void
    {
        $model = new \App\Models\Hero();
        
        $heroId = $model->insert([
            'name'  => 'Test Hero',
            'sport' => 'Testing',
            'slug'  => 'test-hero',
        ]);

        $this->assertIsInt($heroId);
        $this->assertGreaterThan(0, $heroId);

        $saved = $model->find($heroId);
        $this->assertEquals('Test Hero', $saved['name']);
        $this->assertEquals('test-hero', $saved['slug']);
    }
}
