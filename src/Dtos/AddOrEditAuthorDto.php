<?php

namespace App\Dtos;

use App\Annotations\{ReadingProperty, ReadingEntity};
use App\Services\AllowedValuesGetters\{GetAllowedJanreService, GetAllowedLanguageService, GetAllowedCountryService};
use App\Enums\ReaderMode;

#[ReadingEntity(displayName: 'Автор')]
class AddOrEditAuthorDto 
{
    #[ReadingProperty(displayName: 'ID', modes: [ReaderMode::MODE_EDIT, ReaderMode::MODE_DELETE, ReaderMode::MODE_VIEW], isMoreThanZero: true)]
    private ?int $id = 0;
    
    #[ReadingProperty(displayName: 'ФИО автора', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT])]
    private ?string $fullName = '';
    
    #[ReadingProperty(displayName: 'Дата рождения автора', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT], isDate: true)]
    private ?string $birthDate = '';
    
    #[ReadingProperty(displayName: 'Адрес автора', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT])]
    private ?string $address = '';
    
    #[ReadingProperty(displayName: 'Телефон автора', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT])]
    private ?string $phone = '';
    
    #[ReadingProperty(displayName: 'Email автора', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT])]
    private ?string $email = '';
    
    #[ReadingProperty(displayName: 'Язык, на котором пишет автор', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT], allowedValuesPresenterClass: GetAllowedLanguageService::class, showAllowedValues: true)]
    private ?string $language = '';
    
    #[ReadingProperty(displayName: 'Страна автора', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT], allowedValuesPresenterClass: GetAllowedCountryService::class, showAllowedValues: true)]
    private ?string $country = '';
    
    #[ReadingProperty(displayName: 'Жанры, в которых пишет автор', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_EDIT], allowedValuesPresenterClass: GetAllowedJanreService::class, isArrayInAllowed: true, showAllowedValues: true)]
    private ?string $janres = '';
    
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

    public function getLanguage(): ?string 
    {
        return $this->language;
    }
    
     public function setLanguage(?string $language): static 
    {
        $this->language = $language;
        return $this;
    }

    public function getCountry(): ?string 
    {
        return $this->country;
    }
    
    public function setCountry(?string $country): static
    {
        $this->country = $country;
        return $this;
    }

    public function getJanres(): ?string
    {
        return $this->janres;
    }
    
    public function setJanres(?string $janres): static
    {
        $this->janres = $janres;
        return $this;
    }
}
