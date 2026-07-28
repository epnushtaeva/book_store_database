<?php

namespace App\Services\GetEntitiesForMappings;

use App\Models\Reader;
use App\Repositories\ReaderRepository;

class GetReaderService 
{
    public function __construct(
            private ReaderRepository $readerRepository
    ){}
    
    public function getReader(int $readerId): ?Reader
    {
        return $this->readerRepository->find($readerId);
    }
}
