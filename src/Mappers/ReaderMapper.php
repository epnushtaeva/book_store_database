<?php

namespace App\Mappers;

use App\Dtos\{AddOrEditReaderDto, ViewReaderListDto, ViewReaderDto};
use App\Models\Reader;
use App\Constants\CommonConstants;
use App\Services\GetEntitiesForMappings\GetBookService;
use DateTimeImmutable;

class ReaderMapper 
{
   public function __construct(
           private BookMapper $bookMapper,
           private GetBookService $booksService
   ){}
   
   public function addOrEditReaderDtoToReader(?AddOrEditReaderDto $readerDto): ?Reader
   {
       if(!$readerDto){
           return null;
       }
       
       $reader = new Reader();
       $reader->setId($readerDto->getId());
       $reader->setFullName($readerDto->getFullName());
       $reader->setBirthDate(DateTimeImmutable::createFromFormat(CommonConstants::DATE_FORMAT, $readerDto->getBirthDate()));
       $reader->setAddress($readerDto->getAddress());
       $reader->setEmail($readerDto->getEmail());
       $reader->setPhone($readerDto->getPhone());
       return $reader;
   }
   
   public function readersToViewReadersListDto(array $readers): array
   {
       return array_map(fn($reader) => $this->readerToViewReaderListDto($reader), $readers);
   }
   
   public function readerToViewReaderListDto(?Reader $reader): ?ViewReaderListDto
   {
       if(!$reader){
           return null;
       }
       
       $readerDto = new ViewReaderListDto();
       $readerDto->setId($reader->getId());
       $readerDto->setFullName($reader->getFullName());
       $readerDto->setBirthDate($reader->getBirthDate()->format(CommonConstants::DATE_FORMAT));
       $readerDto->setEmail($reader->getEmail());
       $readerDto->setPhone($reader->getPhone());
       $readerDto->setAddress($reader->getAddress());
       return $readerDto;
   }
   
    public function readerToViewReaderDto(?Reader $reader): ?ViewReaderDto
   {
       if(!$reader){
           return null;
       }
       
       $readerDto = new ViewReaderDto();
       $readerDto->setId($reader->getId());
       $readerDto->setFullName($reader->getFullName());
       $readerDto->setBirthDate($reader->getBirthDate()->format(CommonConstants::DATE_FORMAT));
       $readerDto->setEmail($reader->getEmail());
       $readerDto->setPhone($reader->getPhone());
       $readerDto->setAddress($reader->getAddress());
       $readerDto->setBooks(
            $this->bookMapper->booksToReaderBooksDtos(
                 $this->booksService->getBooksForReader($reader->getId())
            )
       );
       return $readerDto;
   }
}
