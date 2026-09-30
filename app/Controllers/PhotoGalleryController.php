<?php

namespace App\Controllers;

use App\Models\PhotoWorkImageModel;
use App\Models\PhotoPrintOptionModel;
use App\Models\PhotoWorkModel;
use App\Models\PhotoCommentModel;
use App\Models\PhotoImageViewModel;
use App\Models\TrackingLinkModel;
use App\Libraries\PhotoSocialImage;

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
        if ($currentImage) {
            $this->recordPhotoView((int) $currentImage['id']);
            $expectedSocialPath = 'uploads/photo-works/social/' . (int) $currentImage['id'] . '-v' . PhotoSocialImage::VERSION . '.jpg';
            if (($currentImage['social_image_path'] ?? '') !== $expectedSocialPath || ! is_file(FCPATH . $expectedSocialPath)) {
                $socialPath = (new PhotoSocialImage())->generate((int) $currentImage['id'], FCPATH . ltrim($currentImage['image_path'], '/\\'));
                if ($socialPath) {
                    (new PhotoWorkImageModel())->update((int) $currentImage['id'], ['social_image_path' => $socialPath]);
                    $currentImage['social_image_path'] = $socialPath;
                }
            }
        }
        $printOptions = [];
        if ($currentImage && !empty($currentImage['is_for_sale'])) {
            $printOptions = (new PhotoPrintOptionModel())->where('photo_work_image_id', $currentImage['id'])->where('is_available', 1)->orderBy('display_order', 'asc')->findAll();
        }
        $shareVersion = $currentImage ? strtotime($currentImage['updated_at'] ?? $currentImage['created_at'] ?? 'now') : time();
        $photoPageUrl = $currentImage ? site_url('fotos/' . $work['slug']) . '?' . http_build_query([
            'imagem' => (int) $currentImage['id'],
            'compartilhar' => $shareVersion,
        ]) : null;
        $trackedShareUrls = $currentImage ? $this->trackedShareUrls($work['slug'], (int) $currentImage['id'], $shareVersion) : [];
        $shareUrl = $trackedShareUrls['whatsapp'] ?? $photoPageUrl;
        // O cartão social deve identificar a fotografia compartilhada. O nome
        // da galeria fica apenas como fallback para fotos sem título próprio.
        $imageTitle = trim((string) ($currentImage['title'] ?? ''))
            ?: trim((string) ($currentImage['alt_text'] ?? ''))
            ?: $work['title'];
        $galleryLabel = trim((string) $work['title']);
        $galleryStem = preg_replace('/s$/u', '', $galleryLabel) ?: $galleryLabel;
        $imageLabel = $imageTitle;
        if (mb_stripos($imageLabel, $galleryStem . ' ') === 0) {
            $imageLabel = trim(mb_substr($imageLabel, mb_strlen($galleryStem)));
        }
        $socialTitle = $galleryStem . ' | ' . ($imageLabel ?: $imageTitle);
        $photoDescription = $currentImage['description'] ?: 'Um quadro de MarcoSanto';
        return view('photo_gallery/show', [
            'title' => $imageTitle . ' | Fotos | James Webb Studio',
            'ogTitle' => $socialTitle,
            'ogDescription' => $photoDescription,
            'work' => $work,
            'currentImage' => $currentImage,
            'currentIndex' => $currentIndex,
            'previousImage' => $images[$currentIndex - 1] ?? null,
            'nextImage' => $images[$currentIndex + 1] ?? null,
            'totalImages' => count($images),
            'printOptions' => $printOptions,
            'ogImage' => !empty($currentImage['social_image_path']) ? base_url($currentImage['social_image_path']) . '?v=' . $shareVersion : (!empty($currentImage['image_path']) ? base_url($currentImage['image_path']) . '?v=' . $shareVersion : null),
            'shareUrl' => $shareUrl,
            'shareUrls' => $trackedShareUrls,
            'ogUrl' => $photoPageUrl,
            'comments' => $currentImage ? $this->commentsForImage((int) $currentImage['id']) : [],
            'commentReturnUrl' => current_url() . ($this->request->getUri()->getQuery() ? '?' . $this->request->getUri()->getQuery() : ''),
        ]);
    }

    private function trackedShareUrls(string $workSlug, int $imageId, int $shareVersion): array
    {
        $destination = site_url('fotos/' . $workSlug) . '?' . http_build_query([
            'imagem' => $imageId,
            'compartilhar' => $shareVersion,
        ]);
        $model = new TrackingLinkModel();
        $urls = [];

        foreach (['whatsapp', 'facebook', 'x'] as $channel) {
            $slug = 'foto-' . $imageId . '-' . $channel;
            $data = [
                'slug' => $slug,
                'destination_url' => $destination,
                'utm_source' => $channel,
                'utm_medium' => 'share',
                'utm_campaign' => 'photo-gallery',
                'utm_content' => 'photo-' . $imageId,
                'is_active' => 1,
            ];
            $existing = $model->where('slug', $slug)->first();
            if ($existing) {
                $model->update($existing->id, $data);
            } elseif (! $model->insert($data)) {
                continue;
            }
            $urls[$channel] = site_url('r/' . $slug);
        }

        return $urls;
    }

    public function comment($slug, $imageId)
    {
        $returnUrl = site_url('fotos/' . $slug) . '?imagem=' . (int) $imageId . '#photo-comments';
        if (! auth()->loggedIn()) {
            session()->setTempdata('beforeLoginUrl', $returnUrl, 3600);
            session()->setFlashdata('commentReturnUrl', $returnUrl);
            return redirect()->to(site_url('login'))->with('message', 'Entre ou crie sua conta para comentar esta fotografia.');
        }

        $image = (new PhotoWorkImageModel())->select('photo_work_images.*, photo_works.slug')
            ->join('photo_works', 'photo_works.id = photo_work_images.photo_work_id')
            ->where('photo_work_images.id', (int) $imageId)
            ->where('photo_works.slug', $slug)
            ->where('photo_works.is_published', 1)
            ->first();
        if (! $image) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $body = trim((string) $this->request->getPost('body'));
        if ($body === '' || mb_strlen($body) > 2000) {
            return redirect()->to($returnUrl)->with('error', 'Escreva um comentário com até 2.000 caracteres.');
        }
        (new PhotoCommentModel())->insert([
            'photo_work_image_id' => (int) $imageId,
            'user_id' => (int) auth()->id(),
            'body' => $body,
            'is_published' => 1,
        ]);
        return redirect()->to($returnUrl)->with('message', 'Comentário publicado.');
    }

    private function commentsForImage(int $imageId): array
    {
        return db_connect()->table('photo_comments')
            ->select('photo_comments.*, users.username, users.display_name')
            ->join('users', 'users.id = photo_comments.user_id')
            ->where('photo_comments.photo_work_image_id', $imageId)
            ->where('photo_comments.is_published', 1)
            ->orderBy('photo_comments.created_at', 'asc')
            ->get()->getResultArray();
    }

    private function recordPhotoView(int $imageId): void
    {
        $userAgent = (string) $this->request->getUserAgent();
        if (preg_match('/bot|crawler|spider|preview|facebookexternal|whatsapp/i', $userAgent)) return;

        $visitorToken = $this->request->getCookie('jws_visitor_token');
        if (! is_string($visitorToken) || ! preg_match('/^[a-f0-9]{64}$/', $visitorToken)) {
            $visitorToken = bin2hex(random_bytes(32));
            $this->response->setCookie('jws_visitor_token', $visitorToken, 60 * 60 * 24 * 365, '', '/', '', false, true, 'Lax');
        }

        $userId = auth()->loggedIn() ? (int) auth()->id() : null;
        if ($userId) {
            db_connect()->table('photo_image_views')
                ->where('visitor_token', $visitorToken)
                ->where('user_id IS NULL', null, false)
                ->set('user_id', $userId)
                ->update();
        }

        (new PhotoImageViewModel())->insert([
            'photo_work_image_id' => $imageId,
            'user_id' => $userId,
            'visitor_token' => $visitorToken,
            'viewed_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
