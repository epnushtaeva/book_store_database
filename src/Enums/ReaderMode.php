<?php

namespace App\Enums;

enum ReaderMode {
    case MODE_CREATE;
    case MODE_EDIT;
    case MODE_DELETE;
    case MODE_VIEW;
}
