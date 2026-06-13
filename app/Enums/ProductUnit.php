<?php

namespace App\Enums;

enum ProductUnit: string
{
    case PCS = 'pcs';
    case KG = 'kg';
    case G = 'g';
    case L = 'l';
    case ML = 'ml';
    case M = 'm';
    case CM = 'cm';
    case BOX = 'box';
    case PACK = 'pack';

    public function label(): string
    {
        return match ($this) {
            self::PCS => 'Piece',
            self::KG => 'Kilogram',
            self::G => 'Gram',
            self::L => 'Litre',
            self::ML => 'Millilitre',
            self::M => 'Metre',
            self::CM => 'Centimetre',
            self::BOX => 'Box',
            self::PACK => 'Pack',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ])->toArray();
    }
}
