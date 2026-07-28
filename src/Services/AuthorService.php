<?php
namespace App\Services;

use App\Repositories\AuthorRepository;
use App\Mappers\AuthorMapper;
use App\Dtos\{AddOrEditAuthorDto, ViewAuthorDto};
use App\Models\Author;

class AuthorService
{
    public function __construct(
            private AuthorRepository $authorRepository,
            private AuthorMapper $authorMapper,
            private BookService $bookService
    ) {}
    
    public function getAuthors(): array
    {
        return $this->authorMapper->authorsToViewAuthorsDtos($this->authorRepository->findAll());
    }
    
    public function getAuthor(?AddOrEditAuthorDto $authorDto): ?ViewAuthorDto
    {
        if(!$authorDto){
            return null;
        }
        
        return $this->authorMapper->authorToViewAuthorDto($this->authorRepository->find($authorDto->getId()));
    }
    
    public function addOrEditAuthor(?AddOrEditAuthorDto $addOrEditAuthorDto): ?Author
    {
        return $this->authorRepository->save($this->authorMapper->addOrEditAuthorDtoToAuthor($addOrEditAuthorDto));
    }
    
    public function removeAuthor(?AddOrEditAuthorDto $author): void
    {
        if($author){
            $this->bookService->removeAllBooksForAuthor($author->getId());
            $this->authorRepository->remove($author->getId());
        }
    }
    
    public function isAuthorExists(int $authorId): bool
    {
        return $this->authorRepository->existsById($authorId);
    }
}
