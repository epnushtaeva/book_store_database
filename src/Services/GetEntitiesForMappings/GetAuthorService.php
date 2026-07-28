<?php

namespace App\Services\GetEntitiesForMappings;

use App\Repositories\AuthorRepository;
use App\Models\Author;

class GetAuthorService 
{
    public function __construct(
            private AuthorRepository $authorRepository
    ) {}
    
     public function getAuthor(int $authorId): ?Author
    {
        return $this->authorRepository->find($authorId);
    }
}
