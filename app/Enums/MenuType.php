<?php

namespace App\Enums;

enum MenuType: string
{
    case GROUP_MENU = 'GROUP_MENU';
    case SINGLE_MENU = 'SINGLE_MENU';
    case HEADER_MENU = 'HEADER_MENU';

    public function label(): string
    {
        return match ($this) {
            self::GROUP_MENU => 'Group Menu',
            self::SINGLE_MENU => 'Single Menu',
            self::HEADER_MENU => 'Header Menu',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type) => [
                $type->value => $type->label(),
            ])
            ->toArray();
    }
}
