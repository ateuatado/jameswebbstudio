<?php

namespace App\Controllers;

use App\Models\CouponModel;

class CouponGift extends BaseController
{
    /**
     * GET /cortesia/{code}
     * Landing page pública de presente — enviada via WhatsApp/link
     */
    public function show(string $code = '')
    {
        $code = strtoupper(trim($code));

        $couponModel = new CouponModel();
        $coupon = $couponModel->where('code', $code)->first();

        // Cupom não encontrado ou já usado
        if (!$coupon || $coupon->used) {
            return view('coupon_gift_error', [
                'code'    => $code,
                'expired' => $coupon && $coupon->used,
            ]);
        }

        // Pacotes ativos com descrição da categoria via JOIN
        $db = \Config\Database::connect();
        $packages = $db->table('packages p')
            ->select('p.*, c.description AS category_description, c.name AS category_name')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->where('p.is_active', 1)
            ->orderBy('p.base_price', 'ASC')
            ->get()->getResult();

        // Pacote mais caro (destaque)
        $maxPackage = !empty($packages) ? end($packages) : null;

        return view('coupon_gift', [
            'coupon'     => $coupon,
            'packages'   => $packages,
            'maxPackage' => $maxPackage,
            'code'       => $code,
        ]);
    }
}
