<?php

namespace App\Models;

use App\Enums\{Country, Language};
use DateTimeImmutable;
use App\Annotations\{Entity, Column, Id};

#[Entity(tableName: 'authors')]
class Author 
{
    #[Id]
    private ?int $id = 0;
    
    #[Column(name: 'full_name')]
    private ?string $fullName = '';
    
    #[Column(name: 'birth_date')]
    private ?DateTimeImmutable $birthDate = null;
    
    #[Column(name: 'address')]
    private ?string $address = '';
    
    #[Column(name: 'phone')]
    private ?string $phone = '';
    
    #[Column(name: 'email')]
    private ?string $email = '';
    
    #[Column(name: 'language')]
    private ?Language $language = null;
    
    #[Column(name: 'country')]
    private ?Country $country = null;
    
    #[Column(name: 'janres', type: 'enum', arrayEnumClass: 'App\\Enums\\Janre')]
    private ?array $janres = [];
    
    public function getId(): ?int 
    {
        return $this->id;
    }
    
    public function setId(?int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getFullName(): ?string 
    {
        return $this->fullName;
    }
    
      public function setFullName(?string $fullName): static
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getBirthDate(): ?DateTimeImmutable 
    {
        return $this->birthDate;
    }
    
    public function setBirthDate(?DateTimeImmutable $birthDate): static 
    {
        $this->birthDate = $birthDate;
        return $this;
    }


    public function getAddress(): ?string 
    {
        return $this->address;
    }
    
    public function setAddress(?string $address): static 
    {
        $this->address = $address;
        return $this;
    }

    public function getPhone(): ?string 
    {
        return $this->phone;
    }
    
    public function setPhone(?string $phone): static 
    {
        $this->phone = $phone;
        return $this;
    }

    public function getEmail(): ?string 
    {
        return $this->email;
    }
    
     public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }
    
     public function setLanguage(?Language $language): static 
    {
        $this->language = $language;
        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }
    
    public function setCountry(?Country $country): static
    {
        $this->country = $country;
        return $this;
    }

    public function getJanres(): ?array 
    {
        return $this->janres;
    }
    
    public function setJanres(?array $janres): static
    {
        $this->janres = $janres;
        return $this;
    }
}
