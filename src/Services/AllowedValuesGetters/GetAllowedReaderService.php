<?php

namespace App\Services\AllowedValuesGetters;

use App\Annotations\GetAllowedValuesInterface;
use App\Repositories\ReaderRepository;

class GetAllowedReaderService implements GetAllowedValuesInterface
{
    public function __construct(
            private ReaderRepository $readerRepository
    ){}

    public function getAllowedValues(string $propertyName): array 
    {
        return array_map(fn($reader) => ['value' => strval($reader->getId()), 'text' => $reader->getFullName()], $this->readerRepository->findAll());
    }
}
