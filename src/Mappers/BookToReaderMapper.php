<?php

namespace App\Mappers;

use App\Dtos\AddOrRemoveBookToReaderDto;
use App\Models\BookToReader;
use App\Services\GetEntitiesForMappings\{GetBookService, GetReaderService};
use App\Dtos\ViewBookToReaderDto;

class BookToReaderMapper 
{
    public function __construct(
            private GetBookService $bookService,
            private GetReaderService $readerService
    ){}
    
    public function AddOrRemoveBookToReaderDtoToBookToReader(?AddOrRemoveBookToReaderDto $bookToReaderDto): ?BookToReader
    {
        if(!$bookToReaderDto){
            return null;
        }
        
        $bookToReader = new BookToReader();
        $bookToReader->setBook($this->bookService->getBook($bookToReaderDto->getBookId()));
        $bookToReader->setReader($this->readerService->getReader($bookToReaderDto->getReaderId()));
        
        if(!$bookToReader->getBook()){
            return null;
        }
        
        if(!$bookToReader->getReader()){
            return null;
        }
        
        return $bookToReader;
    }
    
    public function readerBooksToViewsBookToReaderDtos(array $bookReaders): array
    {
        return array_map(fn($readerBook) => $this->bookReaderToViewBookToReaderDto($readerBook), $bookReaders);
    }
    
    public function bookReaderToViewBookToReaderDto(?BookToReader $bookToReader): ?ViewBookToReaderDto
    {
        if(!$bookToReader){
            return null;
        }
        
        $bookToReaderDto = new ViewBookToReaderDto();
        $bookToReaderDto->setReaderId($bookToReader->getReader()->getId());
        $bookToReaderDto->setReaderFullName($bookToReader->getReader()->getFullName());
        $bookToReaderDto->setBookId($bookToReader->getBook()->getId());
        $bookToReaderDto->setBookName($bookToReader->getBook()->getName());
        $bookToReaderDto->setBookAuthor($bookToReader->getBook()->getAuthor()->getFullName());
        $bookToReaderDto->setBookJanre($bookToReader->getBook()->getJanre()->name);
        return $bookToReaderDto;
    }
}
