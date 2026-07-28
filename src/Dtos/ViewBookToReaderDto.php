<?php

namespace App\Dtos;

use App\Annotations\{ViewProperty, ViewEntity};

#[ViewEntity(displayName: 'Бронирование книги')]
class ViewBookToReaderDto 
{
   #[ViewProperty(displayName: 'ID клиента, забронировавшего книгу')]
   private ?int $readerId = 0;
   
   #[ViewProperty(displayName: 'ФИО клиента, забронировавшего книгу')]
   private ?string $readerFullName = '';
   
   #[ViewProperty(displayName: 'ID книги, которая забронирована')]
   private ?int $bookId = 0;
   
   #[ViewProperty(displayName: 'Название книги, которая забронирована')]
   private ?string $bookName = '';
   
   #[ViewProperty(displayName: 'Жанр книги, которая забронирована')]
   private ?string $bookJanre = '';
   
    #[ViewProperty(displayName: 'Автор книги, которая забронирована')]
   private ?string $bookAuthor = '';
    
   
   public function getReaderId(): ?int 
   {
       return $this->readerId;
   }
   
   public function setReaderId(?int $readerId): static
   {
       $this->readerId = $readerId;
       return $this;
   }

   public function getReaderFullName(): ?string {
       return $this->readerFullName;
   }
   
   public function setReaderFullName(?string $readerFullName): static
   {
       $this->readerFullName = $readerFullName;
       return $this;
   }

   public function getBookId(): ?int {
       return $this->bookId;
   }
   
   public function setBookId(?int $bookId): static
   {
       $this->bookId = $bookId;
       return $this;
   }

   public function getBookName(): ?string {
       return $this->bookName;
   }
   
   public function setBookName(?string $bookName): static
   {
       $this->bookName = $bookName;
       return $this;
   }

   public function getBookJanre(): ?string {
       return $this->bookJanre;
   }
   
   public function setBookJanre(?string $bookJanre): static
   {
       $this->bookJanre = $bookJanre;
       return $this;
   }

   public function getBookAuthor(): ?string {
       return $this->bookAuthor;
   }

   public function setBookAuthor(?string $bookAuthor): static
   {
       $this->bookAuthor = $bookAuthor;
       return $this;
   }
}
