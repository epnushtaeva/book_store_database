<?php
namespace App\Annotations;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ViewProperty 
{
    public function __construct(
             public string $displayName,
             public string $entityClass = ''
    ){}
}
