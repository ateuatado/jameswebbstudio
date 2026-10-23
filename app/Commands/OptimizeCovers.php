<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\ResponsiveCover;

class OptimizeCovers extends BaseCommand
{
    protected $group = 'Media';
    protected $name = 'media:optimize-covers';
    protected $description = 'Generate responsive WebP covers without modifying originals.';

    public function run(array $params)
    {
        $photos = (new \App\Models\Hero())->select('photos.image_path')
            ->join('photos', 'photos.id = heroes.cover_photo_id')->where('heroes.published', 1)->findAll();
        $failed = false;
        foreach (array_unique(array_column($photos, 'image_path')) as $path) {
            try {
                $result = (new ResponsiveCover())->generate($path);
                CLI::write(basename($path) . ': ' . count($result['variants']) . ' variants');
            } catch (\Throwable $error) {
                $failed = true;
                CLI::error(basename($path) . ': ' . $error->getMessage());
            }
        }
        return $failed ? EXIT_ERROR : EXIT_SUCCESS;
    }
}
