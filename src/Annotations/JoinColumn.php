<?php

namespace App\Annotations;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class JoinColumn 
{
    public function __construct(
            public string $name = ''
    ) 
    {
    }
}
