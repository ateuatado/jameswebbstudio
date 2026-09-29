<?php

namespace App\Controllers;

use App\Models\PhotoWorkImageModel;
use App\Models\PhotoPrintOptionModel;
use App\Models\PhotoWorkModel;

class PhotoGalleryController extends BaseController
{
    public function index()
    {
        $collection = trim((string) $this->request->getGet('colecao'));
        $builder = (new PhotoWorkModel())->select('photo_works.*, photo_work_images.id AS image_id, photo_work_images.image_path AS cover_image, photo_work_images.alt_text AS cover_alt')
            ->join('photo_work_images', 'photo_work_images.photo_work_id = photo_works.id AND photo_work_images.is_cover = 1', 'inner')
            ->where('photo_works.is_published', 1);
        if ($collection !== '') $builder->where('photo_works.collection_name', $collection);

        $collections = (new PhotoWorkModel())->select('collection_name')->where('is_published', 1)->where('collection_name IS NOT NULL', null, false)->groupBy('collection_name')->orderBy('collection_name')->findAll();
        $works = $builder->orderBy('photo_works.display_order', 'asc')->orderBy('photo_work_images.display_order', 'asc')->orderBy('photo_work_images.id', 'asc')->findAll();
        $imageCounts = db_connect()->table('photo_work_images')->select('photo_work_id, COUNT(*) AS photo_count')->groupBy('photo_work_id')->get()->getResultArray();
        $countsByWork = array_column($imageCounts, 'photo_count', 'photo_work_id');
        foreach ($works as &$work) $work['photo_count'] = (int) ($countsByWork[$work['id']] ?? 0);
        unset($work);

        return view('photo_gallery/index', [
            'title' => 'Fotos Autorais | James Webb Studio',
            'works' => $works,
            'collections' => $collections,
            'selectedCollection' => $collection,
        ]);
    }

    public function show($slug)
    {
        $work = (new PhotoWorkModel())->where('slug', $slug)->where('is_published', 1)->first();
        if (!$work) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $images = (new PhotoWorkImageModel())->where('photo_work_id', $work['id'])->orderBy('is_cover', 'desc')->orderBy('display_order', 'asc')->findAll();
        $currentIndex = 0;
        $selectedImageId = (int) $this->request->getGet('imagem');
        if ($selectedImageId > 0) {
            foreach ($images as $position => $image) {
                if ((int) $image['id'] === $selectedImageId) {
                    $currentIndex = $position;
                    break;
                }
            }
        }
        $currentImage = $images[$currentIndex] ?? null;
        $printOptions = [];
        if ($currentImage && !empty($currentImage['is_for_sale'])) {
            $printOptions = (new PhotoPrintOptionModel())->where('photo_work_image_id', $currentImage['id'])->where('is_available', 1)->orderBy('display_order', 'asc')->findAll();
        }
        $shareVersion = $currentImage ? strtotime($currentImage['updated_at'] ?? $currentImage['created_at'] ?? 'now') : time();
        return view('photo_gallery/show', [
            'title' => $work['title'] . ' | Fotos | James Webb Studio',
            'ogTitle' => $work['title'] . ' | Fotografia autoral | James Webb Studio',
            'ogDescription' => $work['short_description'] ?: 'Conheça esta fotografia autoral do James Webb Studio.',
            'work' => $work,
            'currentImage' => $currentImage,
            'currentIndex' => $currentIndex,
            'previousImage' => $images[$currentIndex - 1] ?? null,
            'nextImage' => $images[$currentIndex + 1] ?? null,
            'totalImages' => count($images),
            'printOptions' => $printOptions,
            'ogImage' => $currentImage ? site_url('fotos/preview/' . $currentImage['id']) : null,
            'shareUrl' => $currentImage ? site_url('fotos/' . $work['slug']) . '?imagem=' . $currentImage['id'] . '&compartilhar=' . $shareVersion : null,
        ]);
    }

    public function socialPreview($imageId)
    {
        $image = (new PhotoWorkImageModel())
            ->select('photo_work_images.*')
            ->join('photo_works', 'photo_works.id = photo_work_images.photo_work_id')
            ->where('photo_work_images.id', $imageId)
            ->where('photo_works.is_published', 1)
            ->first();
        if (!$image) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $source = FCPATH . ltrim($image['image_path'], '/\\');
        if (!is_file($source)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $directory = FCPATH . 'uploads/photo-works/previews';
        $preview = $directory . DIRECTORY_SEPARATOR . 'social-' . $image['id'] . '.jpg';
        if (!is_dir($directory)) mkdir($directory, 0755, true);
        if (!is_file($preview) || filemtime($preview) < filemtime($source)) $this->createSocialPreview($source, $preview);

        if (!is_file($preview)) return redirect()->to(base_url($image['image_path']));

        return $this->response
            ->setContentType('image/jpeg')
            ->setHeader('Cache-Control', 'public, max-age=604800')
            ->setBody((string) file_get_contents($preview));
    }

    private function createSocialPreview(string $source, string $destination): void
    {
        if (!extension_loaded('gd')) return;
        $details = @getimagesize($source);
        if (!$details) return;

        $original = match ($details[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($source),
            IMAGETYPE_PNG => @imagecreatefrompng($source),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($source) : false,
            default => false,
        };
        if (!$original) return;

        $scale = min(1200 / $details[0], 630 / $details[1], 1);
        $width = max(1, (int) round($details[0] * $scale));
        $height = max(1, (int) round($details[1] * $scale));
        $preview = imagecreatetruecolor($width, $height);
        imagecopyresampled($preview, $original, 0, 0, 0, 0, $width, $height, $details[0], $details[1]);
        imagejpeg($preview, $destination, 85);
        imagedestroy($preview);
        imagedestroy($original);
    }
}
