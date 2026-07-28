<?php

namespace App\Annotations;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ReadingEntity {
    public function __construct(
           public string $displayName 
    ) {}
}
