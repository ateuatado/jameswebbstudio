<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Hero;

class HeroController extends BaseController
{
    protected $heroModel;

    public function __construct()
    {
        $this->heroModel = new Hero();
        helper(['form', 'url']);
    }

    public function index()
    {
        $categoryModel = new \App\Models\CategoryModel();
        $categories = $categoryModel->findAll();
        
        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat->id] = $cat->name;
        }

        $heroes = $this->heroModel->orderBy('created_at', 'desc')->findAll();
        foreach ($heroes as &$h) {
            $h['category_name'] = $categoryMap[$h['category_id'] ?? 0] ?? 'Sem Categoria';
        }
        unset($h);

        $data['heroes'] = $heroes;
        return view('admin/heroes/index', $data);
    }

    public function new()
    {
        $categoryModel = new \App\Models\CategoryModel();
        $data = [
            'title'      => 'Novo Portfólio / Atleta',
            'categories' => $categoryModel->where('is_active', 1)->orderBy('name', 'asc')->findAll()
        ];
        return view('admin/heroes/form', $data);
    }

    public function create()
    {
        $rules = [
            'name'  => 'required|min_length[3]',
            'sport' => 'permit_empty',
            'slug'  => 'required|is_unique[heroes.slug]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $catId = $this->request->getPost('category_id');

        $this->heroModel->save([
            'name'        => $this->request->getPost('name'),
            'sport'       => $this->request->getPost('sport'),
            'category_id' => !empty($catId) ? (int)$catId : null,
            'slug'        => url_title($this->request->getPost('slug'), '-', true)
        ]);

        return redirect()->to(site_url('admin/heroes'))->with('message', 'Portfólio criado com sucesso.');
    }

    public function edit($id = null)
    {
        $hero = $this->heroModel->find($id);
        if (!$hero) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        
        $categoryModel = new \App\Models\CategoryModel();
        
        $data = [
            'title'      => 'Editar Portfólio',
            'hero'       => $hero,
            'categories' => $categoryModel->where('is_active', 1)->orderBy('name', 'asc')->findAll()
        ];
        
        return view('admin/heroes/form', $data);
    }

    public function update($id = null)
    {
        $rules = [
            'name'  => 'required|min_length[3]',
            'sport' => 'permit_empty',
            'slug'  => "required|is_unique[heroes.slug,id,{$id}]"
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $catId = $this->request->getPost('category_id');

        $this->heroModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'sport'       => $this->request->getPost('sport'),
            'category_id' => !empty($catId) ? (int)$catId : null,
            'slug'        => url_title($this->request->getPost('slug'), '-', true)
        ]);

        return redirect()->to(site_url('admin/heroes'))->with('message', 'Portfólio atualizado com sucesso.');
    }

    public function delete($id = null)
    {
        $this->heroModel->delete($id);
        return redirect()->to(site_url('admin/heroes'))->with('message', 'Portfólio excluído.');
    }

    public function photos($heroId)
    {
        $hero = $this->heroModel->find($heroId);
        if (!$hero) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $photoModel = new \App\Models\Photo();
        $data['hero'] = $hero;
        $data['photos'] = $photoModel->where('hero_id', $heroId)->orderBy('display_order', 'asc')->findAll();

        return view('admin/heroes/photos', $data);
    }

    public function uploadPhoto($heroId)
    {
        $file = $this->request->getFile('photo');
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/heroes/', $newName);
            
            $photoModel = new \App\Models\Photo();
            $photoModel->save([
                'hero_id' => $heroId,
                'image_path' => 'uploads/heroes/' . $newName,
                'caption' => $this->request->getPost('caption'),
                'display_order' => $this->request->getPost('display_order') ?? 0
            ]);
        }
        return redirect()->back()->with('message', 'Foto enviada!');
    }

    public function deletePhoto($photoId)
    {
        $photoModel = new \App\Models\Photo();
        $photo = $photoModel->find($photoId);
        if ($photo) {
            if (file_exists(FCPATH . $photo['image_path'])) {
                unlink(FCPATH . $photo['image_path']);
            }
            $photoModel->delete($photoId);
        }
        return redirect()->back()->with('message', 'Foto excluída.');
    }

    public function updatePhoto($photoId)
    {
        $photoModel = new \App\Models\Photo();
        $photo = $photoModel->find($photoId);
        if (!$photo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Foto não encontrada.']);
        }

        $caption      = $this->request->getPost('caption') ?? '';
        $displayOrder = (int) ($this->request->getPost('display_order') ?? 0);

        $photoModel->update($photoId, [
            'caption'       => $caption,
            'display_order' => $displayOrder,
        ]);

        return $this->response->setJSON(['success' => true, 'message' => 'Foto atualizada!']);
    }

    public function setCover($heroId, $photoId)
    {
        $hero = $this->heroModel->find($heroId);
        if (!$hero) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        // Garante que a foto pertence ao herói
        $photoModel = new \App\Models\Photo();
        $photo = $photoModel->where('id', $photoId)->where('hero_id', $heroId)->first();
        if (!$photo) {
            return redirect()->back()->with('error', 'Foto não encontrada.');
        }

        $this->heroModel->update($heroId, ['cover_photo_id' => $photoId]);
        return redirect()->back()->with('message', 'Foto de capa definida com sucesso!');
    }

    public function publish($heroId)
    {
        $hero = $this->heroModel->find($heroId);
        if (!$hero) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $this->heroModel->update($heroId, ['published' => 1]);

        $log = new \App\Models\HeroPublicationLog();
        $log->log((int) $heroId, 'published', 'Publicado manualmente pelo painel.');

        return redirect()->to(site_url('admin/heroes'))->with('message', "✅ Ensaio de {$hero['name']} publicado.");
    }

    public function unpublish($heroId)
    {
        $hero = $this->heroModel->find($heroId);
        if (!$hero) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $reason = $this->request->getPost('reason') ?? '';

        $this->heroModel->update($heroId, ['published' => 0]);

        $log = new \App\Models\HeroPublicationLog();
        $log->log((int) $heroId, 'unpublished', $reason ?: 'Sem motivo informado.');

        return redirect()->to(site_url('admin/heroes'))->with('message', "⏸ Ensaio de {$hero['name']} despublicado.");
    }

    public function cta($heroId)
    {
        $hero = $this->heroModel->find($heroId);
        if (!$hero) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $ctaModel   = new \App\Models\Cta();
        $blockModel = new \App\Models\CtaBlock();
        $data['hero']   = $hero;
        $data['cta']    = $ctaModel->where('hero_id', $heroId)->first();
        $data['blocks'] = $data['cta'] ? $blockModel->blocksForCta((int)$data['cta']['id']) : [];

        return view('admin/heroes/cta', $data);
    }

    public function updateCta($heroId)
    {
        $ctaModel = new \App\Models\Cta();
        $cta = $ctaModel->where('hero_id', $heroId)->first();

        $data = [
            'hero_id' => $heroId,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'button_text' => $this->request->getPost('button_text'),
            'button_url' => $this->request->getPost('button_url')
        ];

        if ($cta) {
            $data['id'] = $cta['id'];
        }

        $ctaModel->save($data);
        return redirect()->to(site_url('admin/heroes/' . $heroId . '/cta'))->with('message', 'CTA salvo com sucesso.');
    }

    // ----------------------------------------------------------------
    // CTA Blocks
    // ----------------------------------------------------------------

    public function ctaBlockCreate($heroId)
    {
        $cta = $this->_ensureCta($heroId);
        $type    = $this->request->getPost('type');
        $content = $this->_extractBlockContent($type);

        // Upload de imagem se houver
        $content = $this->_handleBlockImageUpload($content);

        $blockModel = new \App\Models\CtaBlock();
        $maxOrder = $blockModel->where('cta_id', $cta['id'])->selectMax('display_order')->first();
        $order = (int)($maxOrder['display_order'] ?? 0) + 1;
        $blockModel->saveBlock((int)$cta['id'], $type, $content, $order);

        return redirect()->to(site_url('admin/heroes/' . $heroId . '/cta'))->with('message', 'Bloco adicionado.');
    }

    public function ctaBlockUpdate($heroId, $blockId)
    {
        $blockModel = new \App\Models\CtaBlock();
        $block = $blockModel->find($blockId);
        if (!$block) return redirect()->back()->with('error', 'Bloco não encontrado.');

        $existing = is_string($block['content']) ? json_decode($block['content'], true) ?? [] : [];
        $content  = $this->_extractBlockContent($block['type']);
        $content  = $this->_handleBlockImageUpload($content, $existing);

        $blockModel->updateBlock((int)$blockId, $content);
        return redirect()->to(site_url('admin/heroes/' . $heroId . '/cta'))->with('message', 'Bloco atualizado.');
    }

    public function ctaBlockDelete($heroId, $blockId)
    {
        $blockModel = new \App\Models\CtaBlock();
        $block = $blockModel->find($blockId);
        if ($block) {
            // Remove arquivo de imagem se houver
            $c = is_string($block['content']) ? json_decode($block['content'], true) ?? [] : [];
            foreach (['image_path'] as $field) {
                if (!empty($c[$field]) && file_exists(FCPATH . $c[$field])) {
                    @unlink(FCPATH . $c[$field]);
                }
            }
            $blockModel->delete($blockId);
        }
        return redirect()->to(site_url('admin/heroes/' . $heroId . '/cta'))->with('message', 'Bloco removido.');
    }

    public function ctaBlocksOrder($heroId)
    {
        $order = $this->request->getJSON(true)['order'] ?? [];
        $blockModel = new \App\Models\CtaBlock();
        foreach ($order as $i => $id) {
            $blockModel->update((int)$id, ['display_order' => $i]);
        }
        return $this->response->setJSON(['success' => true]);
    }

    // ----------------------------------------------------------------
    // Helpers privados
    // ----------------------------------------------------------------

    private function _ensureCta(int $heroId): array
    {
        $ctaModel = new \App\Models\Cta();
        $cta = $ctaModel->where('hero_id', $heroId)->first();
        if (!$cta) {
            $ctaModel->insert(['hero_id' => $heroId, 'title' => '', 'description' => '', 'button_text' => '', 'button_url' => '']);
            $cta = $ctaModel->where('hero_id', $heroId)->first();
        }
        return $cta;
    }

    private function _extractBlockContent(string $type): array
    {
        $p = fn($k) => $this->request->getPost($k) ?? '';
        return match($type) {
            'headline'    => ['title' => $p('title'), 'subtitle' => $p('subtitle'), 'image_path' => $p('image_path_existing')],
            'text'        => ['content' => $p('content'), 'align' => $p('align') ?: 'left'],
            'image'       => ['image_path' => $p('image_path_existing'), 'caption' => $p('caption'), 'size' => $p('size') ?: 'contained'],
            'video_embed' => ['url' => $p('url'), 'title' => $p('title')],
            'testimony'   => ['quote' => $p('quote'), 'author' => $p('author'), 'sport' => $p('sport'), 'image_path' => $p('image_path_existing')],
            'process'     => ['steps' => array_map(fn($n,$t,$d) => ['number'=>$n,'title'=>$t,'desc'=>$d],
                                $this->request->getPost('step_number') ?? [],
                                $this->request->getPost('step_title')  ?? [],
                                $this->request->getPost('step_desc')   ?? [])],
            'cta_button'  => ['text' => $p('text'), 'scroll_to_agenda' => (bool)$p('scroll_to_agenda')],
            'spacer'      => ['height' => $p('height') ?: 'md'],
            default       => [],
        };
    }

    private function _handleBlockImageUpload(array $content, array $existing = []): array
    {
        $file = $this->request->getFile('block_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $name = $file->getRandomName();
            $file->move(FCPATH . 'uploads/landing/', $name);
            // Remove imagem anterior se existir
            if (!empty($existing['image_path']) && file_exists(FCPATH . $existing['image_path'])) {
                @unlink(FCPATH . $existing['image_path']);
            }
            $content['image_path'] = 'uploads/landing/' . $name;
        }
        return $content;
    }
}
