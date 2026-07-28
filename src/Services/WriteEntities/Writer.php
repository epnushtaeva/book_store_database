<?php

namespace App\Services\WriteEntities;

interface Writer {
    public function writeEntity(?object $entity, string $entityClassName, bool $writeInOneRow = false, string $spaces =''): void;
    public function writeEntities(array $entities, string $entityClassName, bool $writeInOneRow = false, string $spaces =''): void;
}
