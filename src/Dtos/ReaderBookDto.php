<?php
namespace App\Dtos;

use App\Annotations\{ViewProperty, ViewEntity};

#[ViewEntity(displayName: 'Книга')]
class ReaderBookDto 
{
    #[ViewProperty(displayName: 'ID книги')]
    private ?int $id = 0;
    
    #[ViewProperty(displayName: 'Название книги')]
    private ?string $name = '';
    
    #[ViewProperty(displayName: 'Описание книги')]
    private ?string $description = '';
    
    #[ViewProperty(displayName: 'Автор книги')]
    private ?string $author = '';
    
    #[ViewProperty(displayName: 'Жанр книги')]
    private ?string $janre = null;
    
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
       
    public function getAuthor(): string
    {
        return $this->author;
    }
    
     public function setAuthor(string $author): static 
    {
        $this->author = $author;
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
}
