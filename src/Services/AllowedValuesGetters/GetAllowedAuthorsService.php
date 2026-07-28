<?php

namespace App\Services\AllowedValuesGetters;

use App\Annotations\GetAllowedValuesInterface;
use App\Repositories\AuthorRepository;

class GetAllowedAuthorsService implements GetAllowedValuesInterface
{
    public function __construct(
            private AuthorRepository $authorRepository
    ) {}
    
    public function getAllowedValues(string $propertyName): array 
    {
        return array_map(fn($author) => ['value' => strval($author->getId()), 'text' => $author->getFullName()], $this->authorRepository->findAll());
    }
}
