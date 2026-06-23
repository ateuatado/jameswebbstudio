<?php

namespace App\Models;

use CodeIgniter\Model;

class CouponModel extends Model
{
    protected $table      = 'coupons';
    protected $primaryKey = 'id';

    protected $useTimestamps = true;
    protected $allowedFields = [
        'code', 'email', 'discount_percent',
        'used', 'used_at', 'order_id',
    ];

    protected $returnType = 'object';

    // ─────────────────────────────────────────────────────────────────────────
    // Gera um código único no formato JWS-XXXXX (letras maiúsculas + números)
    // ─────────────────────────────────────────────────────────────────────────
    public function generateUniqueCode(): string
    {
        do {
            $suffix = strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
            $code   = 'JWS-' . $suffix;
        } while ($this->where('code', $code)->first());

        return $code;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Valida cupom pelo código e e-mail: retorna o objeto ou null
    // ─────────────────────────────────────────────────────────────────────────
    public function findValidCoupon(string $code, string $email): ?object
    {
        $coupon = $this
            ->where('code', strtoupper(trim($code)))
            ->where('email', strtolower(trim($email)))
            ->where('used', 0)
            ->first();

        return $coupon ?: null;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Marca cupom como utilizado
    // ─────────────────────────────────────────────────────────────────────────
    public function markAsUsed(int $couponId, int $orderId): void
    {
        $this->update($couponId, [
            'used'     => 1,
            'used_at'  => date('Y-m-d H:i:s'),
            'order_id' => $orderId,
        ]);
    }
}
