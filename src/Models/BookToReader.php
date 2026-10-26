<?php

namespace App\Models;
use App\Annotations\{Entity, Id, JoinColumn};

#[Entity(tableName: 'books_to_readers')]
class BookToReader {
     #[Id]
     private ?int $id = 0;
     
     #[JoinColumn(name: 'book_id')]
     private ?Book $book = null;
     
     #[JoinColumn(name: 'reader_id')]
     private ?Reader $reader = null;
     
     public function getId(): ?int 
     {
         return $this->id;
     }
     
     public function setId(?int $id): static 
     {
         $this->id = $id;
         return $this;
     }

     public function getBook(): ?Book 
     {
         return $this->book;
     }
     
      public function setBook(?Book $book): static 
      {
         $this->book = $book;
         return $this;
     }

     public function getReader(): ?Reader 
     {
         return $this->reader;
     }
     
     public function setReader(?Reader $reader): static 
     {
         $this->reader = $reader;
         return $this;
     }
}
