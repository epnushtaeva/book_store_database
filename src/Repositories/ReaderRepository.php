<?php

namespace App\Repositories;

use App\Models\Reader;

class ReaderRepository extends Repository
{
    protected string $entityClass = Reader::class;
    
    public function find(?int $readerId): ?Reader
    {
        return $this->findEntityById($readerId);
    }
    
    public function findOneByFilter(array $filter): ?Reader
    {
        return $this->findEntityByFilter($filter);
    }
    
    public function save(?Reader $reader): ?Reader
    {
        return $this->saveEntity($reader);
    }
}
