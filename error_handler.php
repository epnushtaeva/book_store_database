<?php

set_exception_handler(function(Throwable $exception){
    error_log(sprintf("Exception %s, file %s, line %d", $exception->getMessage(), $exception->getFile(), $exception->getLine()));
    error_log(implode(',', $exception->getTrace()));
});
