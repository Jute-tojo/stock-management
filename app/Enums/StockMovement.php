<?php

namespace App\Enums;

enum StockMovement: string
{
    case IN = 'in';
    case OUT = 'out';
    case INITIAL = 'initial';

    public function label(): string
    {
        return match ($this) {
            self::IN => 'In',
            self::OUT => 'Out',
            self::INITIAL => 'Initial Stock',
        };
    }
}
