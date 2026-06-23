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

        // Pacote mais caro disponível (para exibir o "valor do presente")
        $pkgModel   = new PackageModel();
        $maxPackage = $pkgModel
            ->where('active', 1)
            ->orderBy('base_price', 'DESC')
            ->first();

        // URL de destino para o checkout
        $checkoutUrl = site_url('investimento?cupom=' . $code);

        return view('coupon_gift', [
            'coupon'       => $coupon,
            'maxPackage'   => $maxPackage,
            'checkoutUrl'  => $checkoutUrl,
            'code'         => $code,
        ]);
    }
}
