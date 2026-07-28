<?php

namespace App\Mappers;

use App\Models\Book;
use App\Dtos\{ViewBookDto, AddOrEditBookDto, AuthorBookDto, ReaderBookDto};
use App\Services\GetEntitiesForMappings\GetAuthorService;
use App\Enums\Janre;

class BookMapper 
{
    public function __construct(
            private GetAuthorService $authorService
    ){}
    
    public function booksToViewBooksDtos(array $books): array
    {
        return array_map(fn($book) => $this->bookToViewBookDto($book), $books);
    }
    
    public function bookToViewBookDto(?Book $book): ?ViewBookDto
    {
        if(!$book){
            return null;
        }
        
        $bookDto = new ViewBookDto();
        $bookDto->setId($book->getId());
        $bookDto->setName($book->getName());
        $bookDto->setDescription($book->getDescription());
        $bookDto->setYear($book->getYear());
        $bookDto->setAuthor($book->getAuthor()->getFullName());
        $bookDto->setAuthorId($book->getAuthor()->getId());
        $bookDto->setJanre($book->getJanre()->name);
        $bookDto->setFreeCount($book->getFreeCount());
        $bookDto->setOnHandCount($book->getOnHandCount());
        return $bookDto;
    }
    
    public function addOrEditBookDtoToBook(?AddOrEditBookDto $bookDto): ?Book
    {
        if(!$bookDto){
            return $bookDto;
        }
        
        $book = new Book();
        $book->setId($bookDto->getId());
        $book->setName($bookDto->getName());
        $book->setDescription($bookDto->getDescription());
        $book->setYear($bookDto->getYear());
        $book->setJanre(Janre::from($bookDto->getJanre()));
        $book->setFreeCount($bookDto->getFreeCount());
        $book->setOnHandCount($bookDto->getOnHandCount());
        $book->setAuthor($this->authorService->getAuthor($bookDto->getAuthor()));
        return $book;
    }
    
    public function booksToAuthorBooksDtos(array $books): array
    {
        return array_map(fn($book) => $this->bookToAuthorDto($book), $books);
    }
    
    public function bookToAuthorDto(?Book $book): ?AuthorBookDto
    {
        if(!$book){
            return $book;
        }
        
        $bookDto = new AuthorBookDto();
        $bookDto->setId($book->getId());
        $bookDto->setName($book->getName());
        $bookDto->setDescription($book->getDescription());
        $bookDto->setJanre($book->getJanre()->name);
        return $bookDto;
    }
    
    public function booksToReaderBooksDtos(array $books): array
    {
        return array_map(fn($book) => $this->bookToReaderDto($book), $books);
    }
    
    public function bookToReaderDto(?Book $book): ?ReaderBookDto
    {
        if(!$book){
            return $book;
        }
        
        $bookDto = new ReaderBookDto();
        $bookDto->setId($book->getId());
        $bookDto->setName($book->getName());
        $bookDto->setDescription($book->getDescription());
        $bookDto->setAuthor($book->getAuthor()->getFullName());
        $bookDto->setJanre($book->getJanre()->name);
        return $bookDto;
    }
}
