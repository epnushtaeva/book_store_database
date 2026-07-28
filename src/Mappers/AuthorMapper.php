<?php

namespace App\Mappers;

use App\Models\Author;
use App\Dtos\{AddOrEditAuthorDto, ViewAuthorDto, ViewAuthorListDto};
use App\Constants\CommonConstants;
use App\Services\GetEntitiesForMappings\GetBookService;
use DateTimeImmutable;
use App\Enums\{Language, Country, Janre};

class AuthorMapper 
{
    public function __construct(
            private BookMapper $bookMapper,
            private GetBookService $booksService
    ){} 
    
    public function addOrEditAuthorDtoToAuthor(?AddOrEditAuthorDto $authorDto): ?Author
    {
        if(!$authorDto){
            return null;
        }
        
        $author =  new Author();
        $author->setId($authorDto->getId());
        $author->setFullName($authorDto->getFullName());
        $author->setBirthDate(DateTimeImmutable::createFromFormat(CommonConstants::DATE_FORMAT, $authorDto->getBirthDate()));
        $author->setAddress($authorDto->getAddress());
        $author->setCountry(Country::from($authorDto->getCountry()));
        $author->setLanguage(Language::from($authorDto->getLanguage()));
        $author->setEmail($authorDto->getEmail());
        $author->setPhone($authorDto->getPhone());
        $author->setJanres(array_map(fn($janreName) => Janre::from($janreName), explode(',', $authorDto->getJanres())));
        return $author;
    }
    
    public function authorsToViewAuthorsDtos(array $authors): array
    {
        return array_map(fn($author) => $this->authorToViewAuthorListDto($author), $authors);
    }
    
    public function authorToViewAuthorListDto(?Author $author): ?ViewAuthorListDto
    {
        if(!$author){
            return null;
        }
        
        $authorDto =  new ViewAuthorListDto();
        $authorDto->setId($author->getId());
        $authorDto->setFullName($author->getFullName());
        $authorDto->setBirthDate($author->getBirthDate()->format(CommonConstants::DATE_FORMAT));
        $authorDto->setAddress($author->getAddress());
        $authorDto->setCountry($author->getCountry()->value);
        $authorDto->setLanguage($author->getLanguage()->value);
        $authorDto->setEmail($author->getEmail());
        $authorDto->setPhone($author->getPhone());
        $authorDto->setJanres($this->janresToJanresString($author->getJanres()));
        return $authorDto;
    }
    
    public function authorToViewAuthorDto(?Author $author): ?ViewAuthorDto
    {
        if(!$author){
            return null;
        }
        
        $authorDto =  new ViewAuthorDto();
        $authorDto->setId($author->getId());
        $authorDto->setFullName($author->getFullName());
        $authorDto->setBirthDate($author->getBirthDate()->format(CommonConstants::DATE_FORMAT));
        $authorDto->setAddress($author->getAddress());
        $authorDto->setCountry($author->getCountry()->value);
        $authorDto->setLanguage($author->getLanguage()->value);
        $authorDto->setEmail($author->getEmail());
        $authorDto->setPhone($author->getPhone());
        $authorDto->setJanres($this->janresToJanresString($author->getJanres()));
        $authorDto->setBooks($this->bookMapper->booksToAuthorBooksDtos(
                   $this->booksService->getBooksForAuthor($author->getId())
                )
        );
        print_r($authorDto);
        return $authorDto;
    }
    
    private function janresToJanresString(array $janres): string
    {
        return implode(', ',array_map(fn($janre) => $janre->name, $janres));
    }
}
