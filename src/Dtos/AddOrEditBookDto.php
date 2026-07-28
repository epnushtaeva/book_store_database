<?php

namespace App\Dtos;

use App\Annotations\{ReadingProperty, ReadingEntity};
use App\Services\AllowedValuesGetters\{GetAllowedJanreService, GetAllowedAuthorsService};
use App\Enums\ReaderMode;

#[ReadingEntity(displayName: 'Книга')]
class AddOrEditBookDto 
{
    #[ReadingProperty(displayName: 'ID', modes: [ReaderMode::MODE_EDIT, ReaderMode::MODE_DELETE, ReaderMode::MODE_VIEW], isMoreThanZero: true)]
    private ?int $id = 0;
    
    #[ReadingProperty(displayName: 'Название книги', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT])]
    private ?string $name = '';
    
    #[ReadingProperty(displayName: 'Описание книги', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT])]
    private ?string $description = '';
    
    #[ReadingProperty(displayName: 'Год издания книги', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT], isMoreThanZero: true)]
    private ?int $year = 0;
    
    #[ReadingProperty(displayName: 'Жанр книги', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT], allowedValuesPresenterClass: GetAllowedJanreService::class, showAllowedValues: true)]
    private ?string $janre = null;
    
    #[ReadingProperty(displayName: 'ID автора книги', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT], allowedValuesPresenterClass: GetAllowedAuthorsService::class)]
    private ?int $author = null;
    
    #[ReadingProperty(displayName: 'Количество экземпляров, доступных для бронирования', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT], isMoreThanZero: true)]
    private ?int $freeCount = 0;
    
    #[ReadingProperty(displayName: 'Количество экземпляров на руках', modes: [ReaderMode::MODE_EDIT])]
    private ?int $onHandCount = 0;
    
    public function getId(): ?int 
    {
        return $this->id;
    }
    
    public function setId(?int $id): static  
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string 
    {
        return $this->name;
    }
    
    public function setName(?string $name): static 
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string 
    {
        return $this->description;
    }
    
     public function setDescription(?string $description): static 
    {
        $this->description = $description;
        return $this;
    }

    public function getYear(): ?int 
    {
        return $this->year;
    }
    
    public function setYear(?int $year): static 
    {
        $this->year = $year;
        return $this;
    }

    public function getJanre(): ?string 
    {
        return $this->janre;
    }
    
     public function setJanre(string $janre): static 
    {
        $this->janre = $janre;
        return $this;
    }

    public function getAuthor(): ?int 
    {
        return $this->author;
    }
    
    public function setAuthor(?int $author): static 
    {
        $this->author = $author;
        return $this;
    }
    
    public function getFreeCount(): ?int 
    {
        return $this->freeCount;
    }
    
    public function setFreeCount(?int $freeCount): static 
    {
        $this->freeCount = $freeCount;
        return $this;
    }

    public function getOnHandCount(): ?int 
    {
        return $this->onHandCount;
    }

    public function setOnHandCount(?int $onHandCount): static 
    {
        $this->onHandCount = $onHandCount;
        return $this;
    }
}
