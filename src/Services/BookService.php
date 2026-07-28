<?php

namespace App\Services;

use App\Repositories\{BookRepository, BookToReaderRepository};
use App\Mappers\{BookMapper, BookToReaderMapper};
use App\Models\{Book, BookToReader};
use App\Dtos\{AddOrEditBookDto, ViewBookDto, AddOrRemoveBookToReaderDto};

class BookService
{
    public function __construct(
            private BookRepository $bookRepository,
            private BookToReaderRepository $bookToReaderRepository,
            private BookMapper $bookMapper,
            private BookToReaderMapper $bookToReaderMapper
    ){}
    
    public function getBooks(): array
    {
        return $this->bookMapper->booksToViewBooksDtos($this->bookRepository->findAll());
    }
    
    public function getReservedBooks(): array
    {
        return $this->bookToReaderMapper->readerBooksToViewsBookToReaderDtos($this->bookToReaderRepository->findAll());
    }
    
    public function getBook(?AddOrEditBookDto $book): ?ViewBookDto
    {
        if(!$book){
            return null;
        }
        
        return $this->bookMapper->bookToViewBookDto($this->bookRepository->find($book->getId()));
    }
    
    public function addOrEditBook(?AddOrEditBookDto $book): ?Book
    {
        return $this->bookRepository->save($this->bookMapper->addOrEditBookDtoToBook($book));
    }
    
    public function removeBook(?AddOrEditBookDto $book): void
    {
         if($book){
            $this->bookToReaderRepository->removeByFilter(['book.id' => $book->getId()]);
            $this->bookRepository->remove($book->getId());
        }
    }
    
    public function isBookCanBeReserved(int $bookId): bool
    {
         $book = $this->bookRepository->find($bookId);
         return $book->getFreeCount() > 0;
    }
    
    public function isBookExists(int $bookId): bool
    {
        return $this->bookRepository->existsById($bookId);
    }
    
    public function reserveBook(?AddOrRemoveBookToReaderDto $addOrRemoveReaderDto): ?BookToReader
    {
        $bookToReader = $this->bookToReaderRepository->save(
                   $this->bookToReaderMapper->AddOrRemoveBookToReaderDtoToBookToReader($addOrRemoveReaderDto)
        );
        
        if($bookToReader){
           $this->updateBooksCountsForReserve($addOrRemoveReaderDto->getBookId());
        }
        
        return $bookToReader;
    }
    
    private function updateBooksCountsForReserve(int $bookId): void
    {
        $book = $this->bookRepository->find($bookId);
        $book->setFreeCount($book->getFreeCount() - 1);
        $book->setOnHandCount($book->getOnHandCount() + 1);
        $this->bookRepository->save($book); 
    }
    
    public function removeReservationOfBook(?AddOrRemoveBookToReaderDto $addOrRemoveReaderDto): void
    {
        if($addOrRemoveReaderDto){
            $this->bookToReaderRepository->removeByFilter(['reader.id' => $addOrRemoveReaderDto->getReaderId(), 'book.id' => $addOrRemoveReaderDto->getBookId()]);
            $this->updateBooksCountsForUnreserve($addOrRemoveReaderDto->getBookId());
        }  
    }
    
    private function updateBooksCountsForUnreserve(int $bookId): void
    {
        $book = $this->bookRepository->find($bookId);
        $book->setFreeCount($book->getFreeCount() + 1);
        $book->setOnHandCount($book->getOnHandCount() - 1);
        $this->bookRepository->save($book); 
    }
    
    public function removeAllBooksForAuthor(?int $authorId): void
    {
        if($authorId){
            $books = $this->bookRepository->findByFilter(['author.id' => $authorId]);
            foreach($books as $book){
                $bookDto = new AddOrEditBookDto();
                $bookDto->setId($book->getId());
                $this->removeBook($book);
            }
        }  
    }
    
    public function unreserveAllBooksForReader(?int $readerId): void
    {
        if($readerId){
            $booksToReader = $this->bookToReaderRepository->findByFilter(['reader.id' => $readerId]);
            
            foreach($booksToReader as $bookToReader){
                $unreserveDto = new AddOrRemoveBookToReaderDto();
                $unreserveDto->setBookId($bookToReader->getBook()->getId());
                $unreserveDto->setReaderId($bookToReader->getReader()->getId());
                $this->removeReservationOfBook($unreserveDto);
            }
        }  
    }
}
