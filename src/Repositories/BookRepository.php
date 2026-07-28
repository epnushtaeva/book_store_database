<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository extends Repository
{   
    public function find(?int $bookId): ?Book
    {
        return $this->findEntityById($bookId);
    }

    public function findOneByFilter(array $filter): ?Book
    {
        return $this->findEntityByFilter($filter);
    }
    
    public function save(?Book $book): ?Book
    {
        return $this->saveEntity($book);
    }
}
