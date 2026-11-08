<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BomClassification: string implements HasLabel
{
    case Design = 'design';
    case Source = 'source';
    case Build = 'build';
    case Analyzed = 'analyzed';
    case Deployed = 'deployed';
    case Runtime = 'runtime';

    public function getLabel(): string
    {
        return match ($this) {
            self::Design => 'Design SBOM',
            self::Source => 'Source SBOM',
            self::Build => 'Build SBOM',
            self::Analyzed => 'Analyzed SBOM',
            self::Deployed => 'Deployed SBOM',
            self::Runtime => 'Runtime SBOM',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::Design => 'Planned components, captured before they exist.',
            self::Source => 'Source files and dependencies from the development environment.',
            self::Build => 'Generated during the build process.',
            self::Analyzed => 'Created by inspecting final software artifacts post-build.',
            self::Deployed => 'Inventory of software installed and configured on a system.',
            self::Runtime => 'Active components and dynamically loaded dependencies at runtime.',
        };
    }
}
