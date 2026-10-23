<?php

namespace App\Controllers;

use App\Libraries\ServiceContent\{Content, Repository};
use CodeIgniter\Exceptions\PageNotFoundException;

class ServicePages extends BaseController
{
    private function repo(): Repository
    {
        $r = new Repository();
        if (! config('ServicePages')->enabled || ! $r->ready()) throw PageNotFoundException::forPageNotFound();
        return $r;
    }

    public function index(?string $slug = null)
    {
        $r = $this->repo();
        $families = $r->table('families')->orderBy('name')->get()->getResultArray();
        $groups = [];
        foreach ($families as $family) {
            if ($slug !== null && $slug !== $family['slug']) continue;
            $pages = $r->publicPages((int) $family['id']);
            if ($pages) {
                foreach ($pages as &$page) {
                    $page['path'] = $r->path($page);
                }
                unset($page);
                $groups[] = ['family' => $family, 'pages' => $pages];
            }
        }
        if (! $groups) throw PageNotFoundException::forPageNotFound();
        $title = $slug ? $groups[0]['family']['name'] : 'Serviços de fotografia';
        return view('service_pages/index', ['title' => $title . ' | James Webb Studio', 'heading' => $title, 'groups' => $groups, 'metaDescription' => $slug ? $groups[0]['family']['description'] : 'Conheça os serviços de fotografia do James Webb Studio e converse sobre o seu projeto.']);
    }

    public function show(string $family, string $slug)
    {
        $r = $this->repo();
        $familyRow = $r->table('families')->where('slug', $family)->get()->getRowArray();
        $page = $familyRow ? $r->table('pages')->where(['family_id' => $familyRow['id'], 'slug' => $slug])->get()->getRowArray() : null;
        if (! $page) {
            $redirect = $r->table('redirects')->where('old_path', 'servicos/' . $family . '/' . $slug)->get()->getRowArray();
            if ($redirect) {
                $target = $r->page((int) $redirect['page_id']);
                if ($target['status'] === 'published') return redirect()->to(site_url($r->path($target)), 301);
            }
            throw PageNotFoundException::forPageNotFound();
        }
        if ($page['status'] !== 'published' || ! $page['published_revision_id']) {
            if ($page['ever_published']) return $this->response->setStatusCode(410)->setHeader('X-Robots-Tag', 'noindex')->setBody('Esta página foi retirada.');
            throw PageNotFoundException::forPageNotFound();
        }
        $revision = $r->revision((int) $page['published_revision_id']);
        return view('service_pages/show', self::viewData($r, $page, $revision));
    }

    public static function viewData(Repository $r, array $page, array $revision, bool $preview = false): array
    {
        $context = $r->table('contexts')->where('id', $revision['context_id'])->get()->getRowArray();
        $data = $revision['data'];
        $url = site_url($r->path($page));
        return ['title' => $data['title'] . ' | James Webb Studio', 'metaDescription' => $data['meta_description'], 'canonicalUrl' => $url, 'metaRobots' => $preview ? 'noindex, follow' : 'index, follow',
            'content' => $data, 'page' => $page, 'family' => $r->family((int) $page['family_id']), 'photos' => $r->photos($data['photo_ids']), 'preview' => $preview,
            'whatsapp' => Content::whatsapp($context['whatsapp'], $data['whatsapp_message'], $url)];
    }
}
