<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum BomType: string implements HasColor, HasLabel
{
    case Sbom = 'sbom';
    case Qbom = 'qbom';
    case Cbom = 'cbom';
    case Aibom = 'aibom';
    case Hbom = 'hbom';

    public function getLabel(): string
    {
        return match ($this) {
            self::Sbom => 'SBOM',
            self::Qbom => 'QBOM',
            self::Cbom => 'CBOM',
            self::Aibom => 'AIBOM',
            self::Hbom => 'HBOM',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::Sbom => 'Software Bill of Materials',
            self::Qbom => 'Quantum Bill of Materials',
            self::Cbom => 'Cryptography Bill of Materials',
            self::Aibom => 'Artificial Intelligence Bill of Materials',
            self::Hbom => 'Hardware Bill of Materials',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Sbom => 'info',
            self::Qbom => 'purple',
            self::Cbom => 'warning',
            self::Aibom => 'success',
            self::Hbom => 'gray',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type): array => [$type->value => $type->getLabel()])
            ->all();
    }
}
