<?php

namespace App\Dtos;

use App\Annotations\{ViewProperty, ViewEntity};

#[ViewEntity(displayName: 'Книга')]
class ViewBookDto {
    #[ViewProperty(displayName: 'ID')]
    private ?int $id = 0;
    
    #[ViewProperty(displayName: 'Название книги')]
    private ?string $name = '';
    
    #[ViewProperty(displayName: 'Описание книги')]
    private ?string $description = '';
    
    #[ViewProperty(displayName: 'Год издания книги')]
    private ?int $year = 0;
    
    #[ViewProperty(displayName: 'Жанр книги')]
    private ?string $janre = null;
    
    #[ViewProperty(displayName: 'Автор книги')]
    private ?string $author = null;
    
    #[ViewProperty(displayName: 'ID автора книги')]
    private ?int $authorId = 0;
    
    #[ViewProperty(displayName: 'Количество экземпляров книги в библиотеке')]
    private ?int $freeCount = 0;
    
    #[ViewProperty(displayName: 'Количество экземпляров книги на руках')]
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

    public function getJanre(): string
    {
        return $this->janre;
    }
    
     public function setJanre(string $janre): static 
    {
        $this->janre = $janre;
        return $this;
    }

    public function getAuthor(): string 
    {
        return $this->author;
    }
    
    public function setAuthor(string $author): static 
    {
        $this->author = $author;
        return $this;
    }
    
    public function getAuthorId(): int 
    {
        return $this->authorId;
    }
    
    public function setAuthorId(int $authorId): static 
    {
        $this->authorId = $authorId;
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
