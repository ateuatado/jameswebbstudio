<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        helper('text');
    }

    public function index()
    {
        $data = [
            'title'      => 'Nichos & Categorias de Fotografia',
            'categories' => $this->categoryModel->orderBy('name', 'asc')->findAll()
        ];
        return view('admin/categories/index', $data);
    }

    public function new()
    {
        return view('admin/categories/form', ['title' => 'Nova Categoria / Nicho']);
    }

    public function create()
    {
        $data = $this->request->getPost();
        
        // Trata checkbox is_active
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        
        if ($this->categoryModel->save($data)) {
            return redirect()->to('/admin/categories')->with('message', 'Categoria criada com sucesso.');
        }

        return redirect()->back()->withInput()->with('errors', $this->categoryModel->errors());
    }

    public function edit($id = null)
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('admin/categories/form', [
            'title'    => 'Editar Categoria / Nicho',
            'category' => $category
        ]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;
        
        // Trata checkbox is_active
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        if ($this->categoryModel->save($data)) {
            return redirect()->to('/admin/categories')->with('message', 'Categoria atualizada com sucesso.');
        }

        return redirect()->back()->withInput()->with('errors', $this->categoryModel->errors());
    }

    public function delete($id = null)
    {
        // Se a categoria for excluída, os pacotes/heróis associados terão category_id alterado para NULL automaticamente (ON DELETE SET NULL nas FKs!)
        $this->categoryModel->delete($id);
        return redirect()->to('/admin/categories')->with('message', 'Categoria removida com sucesso.');
    }
}
