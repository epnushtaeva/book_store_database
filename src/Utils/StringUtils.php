<?php

namespace App\Utils;

abstract class StringUtils {
    static function camelCaseStringToSnakeCase(string $strInCamelCase): string
    {
           return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $strInCamelCase));
    }  
}
