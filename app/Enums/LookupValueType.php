<?php

namespace App\Enums;

enum LookupValueType: string
{
    case BRANCH = 'BRANCH';
    case DEPARTMENT = 'DEPARTMENT';
    case APPROVAL_NAME = 'APPROVAL_NAME';
    case APPROVAL_STATUS = 'APPROVAL_STATUS';
    case JOB_LEVEL = 'JOB_LEVEL';

    public function label(): string
    {
        return match ($this) {
            self::BRANCH => 'Branch',
            self::DEPARTMENT => 'Department',
            self::APPROVAL_NAME => 'Approval Name',
            self::APPROVAL_STATUS => 'Approval Status',
            self::JOB_LEVEL => 'Job Level',
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