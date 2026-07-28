<?php

namespace App\Services\GetEntitiesForMappings;

use App\Repositories\{BookRepository, BookToReaderRepository};
use App\Models\Book;

class GetBookService 
{
     public function __construct(
            private BookRepository $bookRepository,
            private BookToReaderRepository $readerBookRepository
    ){}
    
    public function getBook(int $bookId): ?Book
    {
        return $this->bookRepository->find($bookId);
    }
    
    public function getBooksForAuthor(int $authorId): array
    {
        return $this->bookRepository->findByFilter(['author.id' => $authorId]);
    }
    
    public function getBooksForReader(int $readerId): array
    {
        $readerBooks = $this->readerBookRepository->findByFilter(['reader.id' => $readerId]);
        return array_map(fn($readerBook) => $readerBook->getBook(), $readerBooks);
    }
}
