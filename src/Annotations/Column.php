<?php

namespace App\Annotations;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Column 
{
    public function __construct(
            public string $name = '',
            public bool $isNullable = false,
            public bool $isId = false,
            public bool $isUnique = false,
            public string $type = '',
            public mixed $defaultValue = 0,
            public string $arrayEnumClass = ''
    ) {}
}
