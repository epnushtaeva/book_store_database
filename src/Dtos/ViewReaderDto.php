<?php
namespace App\Dtos;

use App\Annotations\{ViewProperty, ViewEntity};

#[ViewEntity(displayName: 'Клиент библиотеки')]
class ViewReaderDto 
{
    #[ViewProperty(displayName: 'ID')]
    private ?int $id = 0;
    
    #[ViewProperty(displayName: 'ФИО')]
    private ?string $fullName = '';
    
    #[ViewProperty(displayName: 'Дата рождения')]
    private ?string $birthDate = '';
    
    #[ViewProperty(displayName: 'Адрес')]
    private ?string $address = '';
    
    #[ViewProperty(displayName: 'Телефон')]
    private ?string $phone = '';
    
    #[ViewProperty(displayName: 'Email')]
    private ?string $email = '';
    
    #[ViewProperty(displayName: 'Забронированные книги', entityClass: ReaderBookDto::class)]
    private ?array $books = [];
    
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
    
    public function getBooks(): ?array 
    {
        return $this->books;
    }

    public function setBooks(?array $books): static 
    {
        $this->books = $books;
        return $this;
    }
}
