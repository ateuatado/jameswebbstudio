<?php

namespace Tests\Unit;

use App\Filters\DevelopmentToolbar;
use CodeIgniter\Test\CIUnitTestCase;

class DevelopmentToolbarTest extends CIUnitTestCase
{
    public function testPublicResponseDoesNotReceiveDebugAssets(): void
    {
        $response = new \CodeIgniter\HTTP\Response(config('App'));
        $response->setBody('<html>Public page</html>');
        $filter = new DevelopmentToolbar();
        $filter->after(service('request'), $response);
        $this->assertSame('<html>Public page</html>', $response->getBody());
    }
}
