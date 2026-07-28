<?php

namespace App\Annotations;

interface GetAllowedValuesInterface 
{
    public function getAllowedValues(string $propertyName): array;
}
