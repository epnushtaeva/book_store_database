<?php

namespace App\Models;

use App\Enums\{Country, Language};
use DateTimeImmutable;

class Author 
{
    private ?int $id = 0;
    private ?string $fullName = '';
    private ?DateTimeImmutable $birthDate = null;
    private ?string $address = '';
    private ?string $phone = '';
    private ?string $email = '';
    private ?Language $language = null;
    private ?Country $country = null;
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
