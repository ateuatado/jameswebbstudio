<?php

namespace Tests\Controllers;

use CodeIgniter\Test\CIUnitTestCase;

class HomeTest extends \Tests\Support\LocalWebTestCase
{

    public function testIndexLoadsSuccessfully(): void
    {
        $result = $this->get('/');

        $result->assertStatus(200);
        $result->assertSee('Ensaios Realizados');
        $result->assertSee('Estúdio fotográfico na Lapa, São Paulo');
    }
}
