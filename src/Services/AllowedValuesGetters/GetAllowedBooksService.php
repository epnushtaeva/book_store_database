<?php

namespace App\Services\AllowedValuesGetters;

use App\Annotations\GetAllowedValuesInterface;
use App\Repositories\BookRepository;

class GetAllowedBooksService  implements GetAllowedValuesInterface
{
    public function __construct(
            private BookRepository $bookRepository
    ) {}
    
    public function getAllowedValues(string $propertyName): array 
    {
        return array_map(fn($book) => ['value' => strval($book->getId()), 'text' => $book->getName()], $this->bookRepository->findAll());
    }
}