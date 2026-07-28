<?php

namespace App\Services;

use App\Repositories\ReaderRepository;
use App\Mappers\ReaderMapper;
use App\Dtos\{ViewReaderDto, AddOrEditReaderDto};
use App\Models\Reader;

class ReaderService
{
    public function __construct(
            private ReaderRepository $readerRepository,
            private ReaderMapper $readerMapper,
            private BookService $bookService
    ){}
    
    public function getReaders(): array
    {
        return $this->readerMapper->readersToViewReadersListDto($this->readerRepository->findAll());
    }
    
    public function getReader(?AddOrEditReaderDto $readerDto): ?ViewReaderDto
    {
        if(!$readerDto){
            return null;
        }
        
        return $this->readerMapper->readerToViewReaderDto($this->readerRepository->find($readerDto->getId()));
    }
    
    public function addOrEditReader(?AddOrEditReaderDto $readerDto): ?Reader
    {
        return $this->readerRepository->save($this->readerMapper->addOrEditReaderDtoToReader($readerDto));
    }
    
    public function removeReader(?AddOrEditReaderDto $readerDto): void
    {
        if($readerDto){
            $this->bookService->unreserveAllBooksForReader($readerDto->getId());
            $this->readerRepository->remove($readerDto->getId());
        }
    }
    
    public function isReaderExists(int $readerId): bool
    {
        return $this->readerRepository->existsById($readerId);
    }
}
