<?php

namespace App\Repositories;

use App\Models\Author;

class AuthorRepository  extends Repository
{   
    public function find(?int $authorId): ?Author
    {
        return $this->findEntityById($authorId);
    }
    
    public function findOneByFilter(array $filter): ?Author
    {
        return $this->findEntityByFilter($filter);
    }
    
    public function save(?Author $author): ?Author
    {
        return $this->saveEntity($author);
    }
}
