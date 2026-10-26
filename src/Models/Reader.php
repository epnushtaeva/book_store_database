<?php

namespace App\Models;
use DateTimeImmutable;
use App\Annotations\{Entity, Column, Id};

#[Entity(tableName: 'readers')]
class Reader 
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
}
