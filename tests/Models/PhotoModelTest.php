<?php

namespace Tests\Models;

use CodeIgniter\Test\CIUnitTestCase;

class PhotoModelTest extends CIUnitTestCase
{
    use \CodeIgniter\Test\DatabaseTestTrait;

    protected $refresh   = true;
    protected $migrate   = true;
    protected $namespace = 'App';

    public function testInsertPhoto(): void
    {
        $heroModel = new \App\Models\Hero();
        $heroId = $heroModel->insert([
            'name'  => 'Test Hero',
            'sport' => 'Testing',
            'slug'  => 'test-photo-hero',
        ]);

        $model = new \App\Models\Photo();
        $photoId = $model->insert([
            'hero_id' => $heroId,
            'image_path' => 'test.jpg',
            'caption'  => 'Test Caption',
            'display_order' => 1
        ]);

        $this->assertIsInt($photoId);
        $saved = $model->find($photoId);
        $this->assertEquals('test.jpg', $saved['image_path']);
    }
}
