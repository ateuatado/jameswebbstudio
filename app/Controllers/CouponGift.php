<?php

namespace App\Controllers;

use App\Models\CouponModel;
use App\Models\PackageModel;

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

        // Todos os pacotes ativos ordenados por preço
        $pkgModel  = new PackageModel();
        $packages  = $pkgModel
            ->where('is_active', 1)
            ->orderBy('base_price', 'ASC')
            ->findAll();

        // Pacote mais caro (referência de valor do presente)
        $maxPackage = !empty($packages) ? end($packages) : null;

        return view('coupon_gift', [
            'coupon'      => $coupon,
            'packages'    => $packages,
            'maxPackage'  => $maxPackage,
            'code'        => $code,
        ]);
    }
}
