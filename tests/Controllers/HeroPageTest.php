<?php

namespace Tests\Controllers;

use CodeIgniter\Test\CIUnitTestCase;

class HeroPageTest extends \Tests\Support\LocalWebTestCase
{

    public function testHeroPageLoadsCorrectly(): void
    {
        $model = new \App\Models\Hero();
        $model->insert([
            'name'  => 'Fake Athlete',
            'sport' => 'Test Runner',
            'slug'  => 'fake-athlete',
            'published' => 1,
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

    public function testUnpublishedHeroIsNotPublic(): void
    {
        (new \App\Models\Hero())->insert([
            'name' => 'Ensaio privado', 'sport' => 'Retrato',
            'slug' => 'ensaio-privado', 'published' => 0,
        ]);
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);
        $this->get('/ensaio-privado');
    }
}
