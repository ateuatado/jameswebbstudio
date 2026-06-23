<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CouponModel;

class CouponController extends BaseController
{
    // ─────────────────────────────────────────────────────────────────────────
    // GET /admin/coupons — lista todos os cupons
    // ─────────────────────────────────────────────────────────────────────────
    public function index(): string
    {
        $model   = new CouponModel();
        $coupons = $model->orderBy('created_at', 'DESC')->findAll();

        return view('admin/coupons/index', ['coupons' => $coupons]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /admin/coupons/create — formulário de criação
    // ─────────────────────────────────────────────────────────────────────────
    public function create(): string
    {
        return view('admin/coupons/form');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /admin/coupons/store — salva novo cupom
    // ─────────────────────────────────────────────────────────────────────────
    public function store()
    {
        $email   = strtolower(trim($this->request->getPost('email') ?? ''));
        $percent = (int) ($this->request->getPost('discount_percent') ?? 0);

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('error', 'E-mail inválido.');
        }

        if ($percent < 1 || $percent > 100) {
            return redirect()->back()->withInput()->with('error', 'Percentual deve ser entre 1 e 100.');
        }

        $model = new CouponModel();
        $code  = $model->generateUniqueCode();

        $model->insert([
            'code'             => $code,
            'email'            => $email,
            'discount_percent' => $percent,
        ]);

        return redirect()->to(site_url('admin/coupons'))
            ->with('message', "Cupom <strong>{$code}</strong> criado para {$email} com {$percent}% de desconto.");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /admin/coupons/{id}/delete — remove cupom não usado
    // ─────────────────────────────────────────────────────────────────────────
    public function delete(int $id)
    {
        $model  = new CouponModel();
        $coupon = $model->find($id);

        if (!$coupon) {
            return redirect()->to(site_url('admin/coupons'))->with('error', 'Cupom não encontrado.');
        }

        if ($coupon->used) {
            return redirect()->to(site_url('admin/coupons'))->with('error', 'Não é possível remover um cupom já utilizado.');
        }

        $model->delete($id);

        return redirect()->to(site_url('admin/coupons'))
            ->with('message', "Cupom {$coupon->code} removido.");
    }
}
