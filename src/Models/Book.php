<?php

namespace App\Models;
use App\Enums\Janre;

class Book 
{
    private ?int $id = 0;
    private ?string $name = '';
    private ?string $description = '';
    private ?int $year = 0;
    private ?Janre $janre = null;
    private ?Author $author = null;
    private ?int $freeCount = 0;
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

    public function getJanre(): ?Janre 
    {
        return $this->janre;
    }
    
     public function setJanre(?Janre $janre): static 
    {
        $this->janre = $janre;
        return $this;
    }

    public function getAuthor(): ?Author 
    {
        return $this->author;
    }
    
    public function setAuthor(?Author $author): static 
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
