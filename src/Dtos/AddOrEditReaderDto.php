<?php

namespace App\Dtos;

use App\Annotations\{ReadingProperty, ReadingEntity};
use App\Enums\ReaderMode;

#[ReadingEntity(displayName: 'Клиент библиотеки')]
class AddOrEditReaderDto 
{
    #[ReadingProperty(displayName: 'ID', modes: [ReaderMode::MODE_EDIT, ReaderMode::MODE_DELETE, ReaderMode::MODE_VIEW], isMoreThanZero: true)]
    private ?int $id = 0;
    
    #[ReadingProperty(displayName: 'ФИО', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT])]
    private ?string $fullName = '';
    
    #[ReadingProperty(displayName: 'Дата рождения клиента', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT], isDate: true)]
    private ?string $birthDate = '';
    
    #[ReadingProperty(displayName: 'Адрес клиента', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT])]
    private ?string $address = '';
    
    #[ReadingProperty(displayName: 'Телефон клинта', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT])]
    private ?string $phone = '';
    
    #[ReadingProperty(displayName: 'Email клиента', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT])]
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

    public function getBirthDate(): ?string 
    {
        return $this->birthDate;
    }
    
    public function setBirthDate(?string $birthDate): static 
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
