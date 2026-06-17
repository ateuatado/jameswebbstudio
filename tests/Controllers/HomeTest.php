<?php

namespace Tests\Controllers;

use CodeIgniter\Test\CIUnitTestCase;

class HomeTest extends CIUnitTestCase
{
    use \CodeIgniter\Test\FeatureTestTrait;
    use \CodeIgniter\Test\DatabaseTestTrait;

    protected $refresh   = true;
    protected $migrate   = true;
    protected $namespace = 'App';

    public function testIndexLoadsSuccessfully(): void
    {
        $result = $this->get('/');

        $result->assertStatus(200);
        $result->assertSee('Portfolio Heroico');
        $result->assertSee('Alta Performance');
    }
}
