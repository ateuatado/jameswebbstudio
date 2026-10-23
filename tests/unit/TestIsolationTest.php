<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;

class TestIsolationTest extends CIUnitTestCase
{
    public function testDatabaseIsAlwaysEphemeral(): void
    {
        $config = new Database();
        $this->assertSame('testing', ENVIRONMENT);
        $this->assertSame('tests', $config->defaultGroup);
        $this->assertSame('SQLite3', $config->tests['DBDriver']);
        $this->assertSame(':memory:', $config->tests['database']);
        $this->assertSame('', $config->tests['DSN']);
    }
}
