<?php

namespace Tests\Integration;

use App\Libraries\ServiceContent\Repository;
use CodeIgniter\Test\CIUnitTestCase;

class ServiceContentMysqlTest extends CIUnitTestCase
{
    use \CodeIgniter\Test\DatabaseTestTrait;
    protected $refresh = false;
    protected $migrate = true;
    protected $namespace = 'App';

    public function testAdditiveModuleAndPublicationOnMariaDb(): void
    {
        $db = db_connect();
        $this->assertSame('jws_validation_only', $db->database);
        $this->assertSame(33317, (int) $db->port);
        $before = [];
        foreach (['heroes', 'packages', 'orders', 'photo_works'] as $table) $before[$table] = $db->table($table)->countAllResults();
        $r = new Repository($db);
        $suffix = bin2hex(random_bytes(5));
        $r->saveContext('Fotografia de produtos conforme briefing.', '5511999999999', 0);
        $r->createFamily('Produtos ' . $suffix, 'Apresentação de produtos.', 0);
        $family = (int) $r->table('families')->orderBy('id', 'desc')->get(1)->getRowArray()['id'];
        $id = $r->createPage($family, 'Joias ' . $suffix, 0);
        $r->edit($id, 0, ['title' => 'Fotografia de joias', 'h1' => 'Fotografia de joias', 'introduction' => 'Imagens para apresentar seus produtos.', 'meta_description' => 'Converse sobre fotografia de joias.', 'whatsapp_message' => 'Gostaria de um orçamento.', 'sections' => [['heading' => 'Projeto', 'body' => 'O projeto é definido por briefing.']], 'faq' => [], 'photo_ids' => [], 'fact_warnings' => []], 0);
        $r->publish($id, 1, 0);
        $this->assertSame('published', $r->page($id)['status']);
        foreach ($before as $table => $count) $this->assertSame($count, $db->table($table)->countAllResults());
    }
}
