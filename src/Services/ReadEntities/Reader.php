<?php

namespace App\Services\ReadEntities;

use App\Enums\ReaderMode;

interface Reader {
    public function readEntity(string $entityClassName, ReaderMode $readerMode = ReaderMode::MODE_CREATE,  ?callable $checkEntityExistsById = null): ?object;
}
