<?php

namespace Tests\Unit;

use App\Filters\NoIndex;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\Test\CIUnitTestCase;
use Config\App;

class NoIndexTest extends CIUnitTestCase
{
    public function testAddsDirectiveWithoutChangingStatusOrBody(): void
    {
        $response = new Response(new App());
        $response->setStatusCode(200)->setBody('Conteúdo privado');
        (new NoIndex())->after($this->createMock(RequestInterface::class), $response);
        $this->assertSame('noindex, follow', $response->getHeaderLine('X-Robots-Tag'));
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('Conteúdo privado', $response->getBody());
    }
}
