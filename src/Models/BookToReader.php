<?php

namespace App\Models;

class BookToReader {
     private ?int $id = 0;
     private ?Book $book = null;
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
