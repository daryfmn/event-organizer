<?php

namespace App\Support;

class Money
{
    public static function rupiah(float|int|string $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }

    public static function short(float|int|string $amount): string
    {
        $value = (float) $amount;

        if ($value >= 1_000_000_000) {
            return 'Rp '.number_format($value / 1_000_000_000, 2, ',', '.').' M';
        }

        if ($value >= 1_000_000) {
            return 'Rp '.number_format($value / 1_000_000, 2, ',', '.').' Jt';
        }

        if ($value >= 1_000) {
            return 'Rp '.number_format($value / 1_000, 1, ',', '.').' Rb';
        }

        return static::rupiah($value);
    }
}
