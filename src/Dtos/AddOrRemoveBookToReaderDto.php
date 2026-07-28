<?php

namespace App\Dtos;

use App\Annotations\{ReadingProperty, ReadingEntity};
use App\Enums\ReaderMode;
use App\Services\AllowedValuesGetters\{GetAllowedBooksService, GetAllowedReaderService};

#[ReadingEntity(displayName: 'Бронирование книги')]
class AddOrRemoveBookToReaderDto 
{
    #[ReadingProperty(displayName: 'ID клиента библиотеки', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_DELETE], allowedValuesPresenterClass: GetAllowedReaderService::class)]
    private ?int $readerId = 0;
    
    #[ReadingProperty(displayName: 'ID книги', modes: [ReaderMode::MODE_CREATE, ReaderMode::MODE_DELETE], allowedValuesPresenterClass: GetAllowedBooksService::class)]
    private ?int $bookId = 0;
    
    public function getReaderId(): ?int 
    {
        return $this->readerId;
    }
    
    public function setReaderId(?int $readerId): static 
    {
        $this->readerId = $readerId;
        return $this;
    }

    public function getBookId(): ?int 
    {
        return $this->bookId;
    }
    
    public function setBookId(?int $bookId): static 
    {
        $this->bookId = $bookId;
        return $this;
    }


}
