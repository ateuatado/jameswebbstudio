<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/** SQLite fixtures for SEO HTTP tests, not a substitute for MySQL migration tests. */
abstract class LocalWebTestCase extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        // Each simulated request needs its own response headers.
        \Config\Services::injectMock('response', new \CodeIgniter\HTTP\Response(config('App')));
        $db = db_connect();
        if ($db->DBDriver !== 'SQLite3' || $db->database !== ':memory:') {
            throw new \RuntimeException('Local tests require an in-memory database.');
        }
        $tables = [
            'settings' => 'id INTEGER PRIMARY KEY, class TEXT, key TEXT, value TEXT, type TEXT, context TEXT, created_at TEXT, updated_at TEXT',
            'heroes' => 'id INTEGER PRIMARY KEY, name TEXT, sport TEXT, slug TEXT, published INTEGER DEFAULT 0, category_id INTEGER, cover_photo_id INTEGER, created_at TEXT, updated_at TEXT',
            'photos' => 'id INTEGER PRIMARY KEY, hero_id INTEGER, image_path TEXT, caption TEXT, display_order INTEGER, created_at TEXT, updated_at TEXT',
            'categories' => 'id INTEGER PRIMARY KEY, name TEXT, description TEXT, is_active INTEGER',
            'packages' => 'id INTEGER PRIMARY KEY, name TEXT, category_id INTEGER, base_price REAL, is_active INTEGER',
            'ctas' => 'id INTEGER PRIMARY KEY, hero_id INTEGER, title TEXT',
            'cta_blocks' => 'id INTEGER PRIMARY KEY, cta_id INTEGER, type TEXT, content TEXT, display_order INTEGER',
            'photo_works' => 'id INTEGER PRIMARY KEY, slug TEXT, title TEXT, is_published INTEGER, created_at TEXT, updated_at TEXT',
        ];
        foreach ($tables as $table => $columns) {
            $db->query('CREATE TABLE IF NOT EXISTS ' . $table . ' (' . $columns . ')');
            $db->table($table)->emptyTable();
        }
    }
}
