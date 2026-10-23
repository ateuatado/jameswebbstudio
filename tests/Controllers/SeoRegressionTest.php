<?php

namespace Tests\Controllers;

use Tests\Support\LocalWebTestCase;

class SeoRegressionTest extends LocalWebTestCase
{
    public function testAnonymousVisitorCannotOpenAdministration(): void
    {
        $this->get('/admin')->assertRedirect();
    }

    public function testAnonymousVisitorCannotOpenClientArea(): void
    {
        $this->get('/client/meus-ensaios')->assertRedirect();
    }

    public function testPricingStillLoads(): void
    {
        $result = $this->get('/investimento');
        $result->assertStatus(200);
        $result->assertSee('Pacotes de');
    }

    public function testPrivacyStillLoads(): void
    {
        $this->get('/politica-de-privacidade')->assertStatus(200);
    }

    public function testSchedulingRemainsAccessibleButNotIndexable(): void
    {
        (new \App\Models\Hero())->insert([
            'name' => 'Ensaio Local', 'sport' => 'Retrato',
            'slug' => 'ensaio-local', 'published' => 1,
        ]);
        $result = $this->get('/ensaio-local/agendar');
        $result->assertStatus(200);
        $result->assertSee('noindex, follow');
        $this->assertSame('noindex, follow', $result->response()->getHeaderLine('X-Robots-Tag'));
    }

    public function testHomeMetadataAndLinks(): void
    {
        $result = $this->get('/');
        $result->assertStatus(200);
        $result->assertSee('Estúdio fotográfico na Lapa, São Paulo');
        $result->assertSee('Consultar pacotes e valores');
        $result->assertSee('LocalBusiness');
        $result->assertDontSee('aggregateRating');
        $result->assertDontSee('PhotographAction');
        $this->assertSame('', $result->response()->getHeaderLine('X-Robots-Tag'));
    }

    public function testSitemapOnlyContainsPublishedContent(): void
    {
        $model = new \App\Models\Hero();
        $model->insert(['name' => 'Público', 'sport' => 'Retrato', 'slug' => 'publico', 'published' => 1]);
        $model->insert(['name' => 'Privado', 'sport' => 'Retrato', 'slug' => 'privado', 'published' => 0]);
        $result = $this->get('/sitemap.xml');
        $result->assertStatus(200);
        $xml = simplexml_load_string((string) $result->response()->getBody());
        $this->assertNotFalse($xml);
        $result->assertSee('/investimento');
        $result->assertSee('/publico');
        $result->assertDontSee('/privado');
        $result->assertDontSee('/pacotes');
    }
}
