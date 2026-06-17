<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContractSectionModel;

class ContractSectionController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new ContractSectionModel();
    }

    // ── Lista todas as cláusulas ──
    public function index()
    {
        $sections = $this->model->orderBy('display_order', 'asc')->findAll();

        return view('admin/contract/index', [
            'sections' => $sections,
        ]);
    }

    // ── Form nova cláusula ──
    public function create()
    {
        return view('admin/contract/form', [
            'section' => null,
        ]);
    }

    // ── Salvar nova ──
    public function store()
    {
        $data = [
            'title'         => trim($this->request->getPost('title')),
            'content'       => trim($this->request->getPost('content')),
            'display_order' => (int) $this->request->getPost('display_order'),
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if (!$this->model->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        return redirect()->to('/admin/contract-sections')->with('message', 'Cláusula criada com sucesso.');
    }

    // ── Form editar ──
    public function edit($id)
    {
        $section = $this->model->find($id);
        if (!$section) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        return view('admin/contract/form', [
            'section' => $section,
        ]);
    }

    // ── Atualizar ──
    public function update($id)
    {
        $section = $this->model->find($id);
        if (!$section) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $data = [
            'title'         => trim($this->request->getPost('title')),
            'content'       => trim($this->request->getPost('content')),
            'display_order' => (int) $this->request->getPost('display_order'),
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if (!$this->model->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        return redirect()->to('/admin/contract-sections')->with('message', 'Cláusula atualizada com sucesso.');
    }

    // ── Excluir ──
    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->to('/admin/contract-sections')->with('message', 'Cláusula removida.');
    }

    // ── Preview PDF com dados de exemplo ──
    public function preview()
    {
        $sections = $this->model->getActive();

        // Dados de exemplo para preencher placeholders
        $placeholders = [
            '{nome_cliente}'       => 'João da Silva',
            '{cpf_cliente}'        => '123.456.789-00',
            '{estado_civil}'       => 'solteiro(a)',
            '{endereco_completo}'  => 'Rua Exemplo, 123 - Vila Teste, São Paulo/SP, CEP 01234-567',
            '{email}'              => 'joao@exemplo.com',
            '{telefone}'           => '(11) 99999-0000',
            '{nome_pacote}'        => 'Pacote Essencial',
            '{valor}'              => '1.200,00',
            '{qtd_fotos}'          => '20',
            '{valor_foto_extra}'   => '60,00',
            '{forma_pagamento}'    => 'cartão de crédito',
            '{data_contratacao}'   => date('d/m/Y'),
            '{autorizacao_imagem}' => 'AUTORIZA',
        ];

        // Monta conteúdo simples em texto para preview
        $html  = '<html><head><meta charset="utf-8">';
        $html .= '<style>';
        $html .= 'body { font-family: Arial, sans-serif; font-size: 13px; color: #222; max-width: 700px; margin: 40px auto; padding: 0 20px; }';
        $html .= 'h1 { text-align: center; font-size: 20px; margin-bottom: 5px; }';
        $html .= 'h2 { font-size: 14px; margin-top: 25px; margin-bottom: 8px; text-transform: uppercase; border-bottom: 1px solid #ccc; padding-bottom: 4px; }';
        $html .= '.clause-content { white-space: pre-wrap; line-height: 1.7; }';
        $html .= '.footer { margin-top: 50px; text-align: center; font-size: 11px; color: #888; }';
        $html .= '.signatures { margin-top: 60px; display: flex; justify-content: space-between; }';
        $html .= '.sig-block { text-align: center; width: 45%; }';
        $html .= '.sig-line { border-top: 1px solid #333; margin-top: 60px; padding-top: 5px; font-size: 12px; }';
        $html .= '</style></head><body>';
        $html .= '<h1>CONTRATO DE PRESTAÇÃO DE SERVIÇOS FOTOGRÁFICOS</h1>';
        $html .= '<p style="text-align:center;color:#888;font-size:11px;">São Paulo, ' . date('d/m/Y') . '</p>';

        $clauseNum = 1;
        foreach ($sections as $s) {
            $content = $s->content;
            foreach ($placeholders as $key => $value) {
                $content = str_replace($key, $value, $content);
            }
            $html .= '<h2>Cláusula ' . $clauseNum . 'ª — ' . esc($s->title) . '</h2>';
            $html .= '<div class="clause-content">' . nl2br(esc($content)) . '</div>';
            $clauseNum++;
        }

        $html .= '<div class="signatures">';
        $html .= '<div class="sig-block"><div class="sig-line">CONTRATADO<br>Marco Santo</div></div>';
        $html .= '<div class="sig-block"><div class="sig-line">CONTRATANTE<br>João da Silva</div></div>';
        $html .= '</div>';
        $html .= '<div class="footer">Documento gerado para pré-visualização — sem validade jurídica.</div>';
        $html .= '</body></html>';

        return $this->response
                     ->setHeader('Content-Type', 'text/html; charset=utf-8')
                     ->setBody($html);
    }
}
