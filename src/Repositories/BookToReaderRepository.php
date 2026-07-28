<?php

namespace App\Repositories;

use App\Models\BookToReader;

class BookToReaderRepository extends Repository
{ 
    public function find(?int $bookToReaderId): ?BookToReader
    {
        return $this->findEntityById($bookToReaderId);
    }
    
    public function findOneByFilter(array $filter): ?BookToReader
    {
        return $this->findEntityByFilter( $filter);
    }
    
    public function save(?BookToReader $bookToReader): ?BookToReader
    {
        return $this->saveEntity($bookToReader);
    }
}
