<?php

namespace App\Services\AllowedValuesGetters;

use App\Annotations\GetAllowedValuesInterface;
use App\Enums\Janre;

class GetAllowedJanreService implements GetAllowedValuesInterface
{
    public function getAllowedValues(string $propertyName): array  
    {
        return array_map(fn($case) => ['value' => $case->value, 'text' => ''],  Janre::cases());
    }
}
