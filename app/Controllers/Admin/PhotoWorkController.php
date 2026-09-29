<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PhotoWorkImageModel;
use App\Models\PhotoPrintOptionModel;
use App\Models\PhotoWorkModel;

class PhotoWorkController extends BaseController
{
    private PhotoWorkModel $works;
    private PhotoWorkImageModel $images;
    private PhotoPrintOptionModel $printOptions;

    public function __construct()
    {
        $this->works = new PhotoWorkModel();
        $this->images = new PhotoWorkImageModel();
        $this->printOptions = new PhotoPrintOptionModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $works = $this->works->select('photo_works.*, photo_work_images.image_path AS cover_image')
            ->join('photo_work_images', 'photo_work_images.photo_work_id = photo_works.id AND photo_work_images.is_cover = 1', 'left')
            ->orderBy('photo_works.display_order', 'asc')->orderBy('photo_works.created_at', 'desc')->findAll();

        return view('admin/photo_works/index', ['title' => 'Acervo de Fotos', 'works' => $works]);
    }

    public function new()
    {
        return view('admin/photo_works/form', ['title' => 'Nova Galeria de Fotos']);
    }

    public function create()
    {
        $data = $this->workData();
        if (!$this->works->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $this->works->errors());
        }
        return redirect()->to(site_url('admin/fotos/' . $this->works->getInsertID() . '/imagens'))->with('message', 'Obra criada. Envie a imagem de capa para publicá-la.');
    }

    public function edit($id = null)
    {
        $work = $this->works->find($id);
        if (!$work) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('admin/photo_works/form', ['title' => 'Editar Galeria de Fotos', 'work' => $work]);
    }

    public function update($id = null)
    {
        $data = $this->workData();
        $data['id'] = (int) $id;
        if (!$this->works->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->works->errors());
        }
        return redirect()->to(site_url('admin/fotos'))->with('message', 'Obra atualizada.');
    }

    public function delete($id = null)
    {
        foreach ($this->images->where('photo_work_id', $id)->findAll() as $image) $this->removeFile($image['image_path']);
        $this->works->delete($id);
        return redirect()->to(site_url('admin/fotos'))->with('message', 'Obra e suas imagens foram removidas.');
    }

    public function images($id)
    {
        $work = $this->works->find($id);
        if (!$work) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('admin/photo_works/images', ['work' => $work, 'images' => $this->images->where('photo_work_id', $id)->orderBy('display_order', 'asc')->findAll()]);
    }

    public function uploadImage($id)
    {
        if (!$this->works->find($id)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $file = $this->request->getFile('image');
        if (!$file || !$file->isValid() || !in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'], true) || $file->getSize() > 10 * 1024 * 1024) {
            return redirect()->back()->with('error', 'Envie uma imagem JPG, PNG ou WebP de até 10 MB.');
        }

        $directory = FCPATH . 'uploads/photo-works';
        if (!is_dir($directory)) mkdir($directory, 0755, true);
        $name = $file->getRandomName();
        $file->move($directory, $name);
        $hasImages = $this->images->where('photo_work_id', $id)->countAllResults() > 0;
        $this->images->insert([
            'photo_work_id' => (int) $id,
            'image_path' => 'uploads/photo-works/' . $name,
            'alt_text' => trim((string) $this->request->getPost('alt_text')),
            'is_cover' => $hasImages ? 0 : 1,
            'display_order' => (int) ($this->request->getPost('display_order') ?? 0),
        ]);
        return redirect()->back()->with('message', 'Imagem enviada.');
    }

    public function setCover($workId, $imageId)
    {
        $image = $this->images->where('id', $imageId)->where('photo_work_id', $workId)->first();
        if (!$image) return redirect()->back()->with('error', 'Imagem não encontrada.');
        db_connect()->table('photo_work_images')->where('photo_work_id', $workId)->update(['is_cover' => 0]);
        $this->images->update($imageId, ['is_cover' => 1]);
        return redirect()->back()->with('message', 'Imagem de capa definida.');
    }

    public function deleteImage($workId, $imageId)
    {
        $image = $this->images->where('id', $imageId)->where('photo_work_id', $workId)->first();
        if ($image) { $this->removeFile($image['image_path']); $this->images->delete($imageId); }
        return redirect()->back()->with('message', 'Imagem removida.');
    }

    public function printOptions($workId, $imageId)
    {
        $work = $this->works->find($workId);
        $image = $this->images->where('id', $imageId)->where('photo_work_id', $workId)->first();
        if (!$work || !$image) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        return view('admin/photo_works/print_options', [
            'work' => $work,
            'image' => $image,
            'options' => $this->printOptions->where('photo_work_image_id', $imageId)->orderBy('display_order', 'asc')->findAll(),
        ]);
    }

    public function savePrintOptions($workId, $imageId)
    {
        $image = $this->images->where('id', $imageId)->where('photo_work_id', $workId)->first();
        if (!$image) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $this->images->update($imageId, [
            'is_for_sale' => $this->request->getPost('is_for_sale') ? 1 : 0,
            'accepts_custom_sizes' => $this->request->getPost('accepts_custom_sizes') ? 1 : 0,
            'custom_size_note' => trim((string) $this->request->getPost('custom_size_note')) ?: null,
        ]);

        $option = [
            'photo_work_image_id' => (int) $imageId,
            'size_label' => trim((string) $this->request->getPost('size_label')),
            'width_cm' => $this->request->getPost('width_cm') ?: null,
            'height_cm' => $this->request->getPost('height_cm') ?: null,
            'price_cents' => (int) round((float) str_replace(',', '.', (string) $this->request->getPost('price')) * 100),
            'is_available' => $this->request->getPost('is_available') ? 1 : 0,
            'display_order' => (int) ($this->request->getPost('display_order') ?? 0),
        ];
        if ($option['size_label'] !== '' && $option['price_cents'] > 0 && !$this->printOptions->insert($option)) {
            return redirect()->back()->withInput()->with('errors', $this->printOptions->errors());
        }

        return redirect()->to(site_url("admin/fotos/{$workId}/imagens/{$imageId}/edicoes"))->with('message', 'Opções comerciais atualizadas.');
    }

    public function deletePrintOption($workId, $imageId, $optionId)
    {
        $option = $this->printOptions->where('id', $optionId)->where('photo_work_image_id', $imageId)->first();
        if ($option) $this->printOptions->delete($optionId);
        return redirect()->to(site_url("admin/fotos/{$workId}/imagens/{$imageId}/edicoes"))->with('message', 'Tamanho removido.');
    }

    public function editPrintOption($workId, $imageId, $optionId)
    {
        $work = $this->works->find($workId);
        $image = $this->images->where('id', $imageId)->where('photo_work_id', $workId)->first();
        $option = $this->printOptions->where('id', $optionId)->where('photo_work_image_id', $imageId)->first();
        if (!$work || !$image || !$option) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        return view('admin/photo_works/edit_print_option', compact('work', 'image', 'option'));
    }

    public function updatePrintOption($workId, $imageId, $optionId)
    {
        $option = $this->printOptions->where('id', $optionId)->where('photo_work_image_id', $imageId)->first();
        if (!$option) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $data = [
            'id' => (int) $optionId,
            'size_label' => trim((string) $this->request->getPost('size_label')),
            'width_cm' => $this->request->getPost('width_cm') ?: null,
            'height_cm' => $this->request->getPost('height_cm') ?: null,
            'price_cents' => (int) round((float) str_replace(',', '.', (string) $this->request->getPost('price')) * 100),
            'is_available' => $this->request->getPost('is_available') ? 1 : 0,
            'display_order' => (int) ($this->request->getPost('display_order') ?? 0),
        ];
        if (!$this->printOptions->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->printOptions->errors());
        }

        return redirect()->to(site_url("admin/fotos/{$workId}/imagens/{$imageId}/edicoes"))->with('message', 'Tamanho atualizado.');
    }

    private function workData(): array
    {
        $isPublished = $this->request->getPost('is_published') ? 1 : 0;
        return [
            'title' => trim((string) $this->request->getPost('title')),
            'slug' => url_title((string) ($this->request->getPost('slug') ?: $this->request->getPost('title')), '-', true),
            'short_description' => trim((string) $this->request->getPost('short_description')) ?: null,
            'story' => trim((string) $this->request->getPost('story')) ?: null,
            'work_date' => $this->request->getPost('work_date') ?: null,
            'location' => trim((string) $this->request->getPost('location')) ?: null,
            'technique' => trim((string) $this->request->getPost('technique')) ?: null,
            'collection_name' => trim((string) $this->request->getPost('collection_name')) ?: null,
            'credits' => trim((string) $this->request->getPost('credits')) ?: null,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? date('Y-m-d H:i:s') : null,
            'display_order' => (int) ($this->request->getPost('display_order') ?? 0),
        ];
    }

    private function removeFile(string $path): void
    {
        $file = FCPATH . ltrim($path, '/\\');
        if (is_file($file)) unlink($file);
    }
}
