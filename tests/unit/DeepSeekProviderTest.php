<?php

namespace Tests\Unit;

use App\Libraries\ServiceContent\DeepSeekProvider;
use CodeIgniter\Test\CIUnitTestCase;

class DeepSeekProviderTest extends CIUnitTestCase
{
    private array $original;

    protected function setUp(): void
    {
        parent::setUp();
        $config = config('ServicePages');
        $this->original = [$config->apiKey, $config->generationEnabled];
        $config->apiKey = 'unit-test-placeholder'; $config->generationEnabled = true;
    }

    protected function tearDown(): void
    {
        [config('ServicePages')->apiKey, config('ServicePages')->generationEnabled] = $this->original;
        \Config\Services::resetSingle('curlrequest');
        parent::tearDown();
    }

    public function testInvalidJsonDoesNotEscapeProviderBody(): void
    {
        $response = new \CodeIgniter\HTTP\Response(config('App'));
        $response->setStatusCode(200)->setBody('{"choices":[{"finish_reason":"stop","message":{"content":"broken-json"}}]}');
        $http = $this->createMock(\CodeIgniter\HTTP\CURLRequest::class);
        $http->expects($this->once())->method('post')->with('https://api.deepseek.com/chat/completions', $this->callback(fn ($options) => $options['json']['response_format']['type'] === 'json_object' && $options['allow_redirects'] === false))->willReturn($response);
        \Config\Services::injectMock('curlrequest', $http);
        try { (new DeepSeekProvider())->generate('Joias', 'Produtos', 'Oferta real.'); $this->fail('Expected error'); }
        catch (\RuntimeException $error) {
            $this->assertStringNotContainsString('broken-json', $error->getMessage());
            $this->assertStringNotContainsString('unit-test-placeholder', $error->getMessage());
        }
    }

    public function testHttpErrorDoesNotLeakBodyOrKey(): void
    {
        $response = new \CodeIgniter\HTTP\Response(config('App'));
        $response->setStatusCode(401)->setBody('sensitive provider body');
        $http = $this->createMock(\CodeIgniter\HTTP\CURLRequest::class);
        $http->method('post')->willReturn($response);
        \Config\Services::injectMock('curlrequest', $http);
        $this->expectExceptionMessage('Fornecedor retornou HTTP 401. Nenhuma publicação realizada.');
        (new DeepSeekProvider())->generate('Joias', 'Produtos', 'Oferta real.');
    }
}
