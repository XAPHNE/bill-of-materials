<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BomLevel: string implements HasLabel
{
    case TopLevel = 'top_level';
    case NLevel = 'n_level';
    case Delivery = 'delivery';
    case Transitive = 'transitive';
    case Complete = 'complete';

    public function getLabel(): string
    {
        return match ($this) {
            self::TopLevel => 'Top-level SBOM',
            self::NLevel => 'n-level SBOM',
            self::Delivery => 'Delivery SBOM',
            self::Transitive => 'Transitive SBOM',
            self::Complete => 'Complete SBOM',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::TopLevel => 'Components integrated or directly used in the product.',
            self::NLevel => 'Deeper, multi-tier detail of components and their interactions.',
            self::Delivery => 'Every part, library and dependency in the delivered release.',
            self::Transitive => 'Direct and indirect (transitive) dependencies.',
            self::Complete => 'Exhaustive list of all parts, dependencies and metadata.',
        };
    }
}
