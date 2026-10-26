<?php

namespace App\Repositories\Utils;

use App\Annotations\{Column, Id};
use App\Constants\CommonConstants;
use ReflectionProperty, ReflectionClass, DateTimeImmutable;
use App\Enums\{Language, Country,  Janre, ReaderMode};

abstract class ValueUtils 
{
    public static function getValues(string $entityClass, object $entity): array
    {
        $values = [];
        $class = new ReflectionClass($entityClass);
        
        foreach($class->getProperties() as $property){
            $columnAttribute = ColumnUtils::getColumnAttribute($property);
            
            if($columnAttribute){
                $values []= self::formatColumnValue($property, $columnAttribute, $entity);
            } else {
                $joinColumn = ColumnUtils::getJoinColumnAttribute($property);
                
                if($joinColumn){
                    $values []= self::getJoinColumnValue($property, $entity);
                }
            }
        }
        
        return $values;
    }
    
    public static function getIdSql(string $entityClass, object $entity): string
    {
         $class = new ReflectionClass($entityClass);
         $id = null;
         $idColumn = null;
        
        foreach($class->getProperties() as $property){
            $idAttribute = self::getIdAttribute($property);
            
            if($idAttribute){
                $id = $property->getValue($entity);
                $idColumn = ColumnUtils::getColumnName($property);
                break;
            }
        }
        
        if(!$idColumn){
            $idColumn = 'id';
        }
        
        if(!$id){
            $id = -1;
        }
        
        return "$idColumn = $id";
    }
    
    public static function formatValueForColumn( 
            ReflectionProperty $entityProperty, 
            Column $columnAttribute,
            mixed $value
    ): string
    {
        
         if(ColumnUtils::isColumnNumeric($columnAttribute, $entityProperty->getType())){
                return self::formatNumericValue($value, $columnAttribute);
        }

        if(ColumnUtils::isColumnDate($columnAttribute, $entityProperty->getType())){
                return self::formatDateValue($value, $columnAttribute);
        }

            
       
        if(!in_array($entityProperty->getType(), ['string', '?string']) && !is_array($value) && (new ReflectionClass($value))->isEnum()){
                return "'".$value->value."'";
        }
        
        if(is_array($value)){
            $arrayType = $columnAttribute->type;
            
            switch($arrayType){
                case 'enum':
                    return "'".implode(',', array_map(fn($el)=>$el->value, $value))."'";
                default:
                    return "'".implode(',', $value)."'";
            }
        }
        
        if($value != 'null'){
            $value = "'$value'";
        }
        
        return $value;
    }
    
    private static function formatColumnValue(
            ReflectionProperty $entityProperty, 
            Column $columnAttribute,
            object $entity
    ): string
    {
        $propertyType = $entityProperty->getType();
        $value = self::processNullValue($entityProperty->getValue($entity), $columnAttribute);
        return self::formatValueForColumn($entityProperty, $columnAttribute, $value);
    }
    
    private static function processNullValue(mixed $value, Column $columnAttribute): mixed
    {
        if($value == null){
                if(!$columnAttribute->isNullable){
                    $value = $columnAttribute->defaultValue;
                } else {
                    $value = 'null';
                }
        }
        
        return $value;
    }
    
    private static function getJoinColumnValue(ReflectionProperty $entityProperty, object $entity): string
    {
        $value = $entityProperty->getValue($entity);
        
        if($value){
            return strval($value->getId());
        } else {
            return 'null';
        }
    }
    
    private static function formatNumericValue(mixed $value, ?Column $columnAttribute): string
    {   
        return strval(self::setEmptyStringAsNull($value, $columnAttribute));
    }
    
    private static function formatDateValue(mixed $value, Column $columnAttribute): string
    {
        $value = self::setEmptyStringAsNull($value, $columnAttribute);
        
        if($value instanceof DateTimeImmutable){
                $value = $value->format(CommonConstants::SQL_DATE_FORMAT);
        }
        
        return self::formatStringValue($value);
    }
    
    private static function formatStringValue(mixed $value): string 
    {
        if($value != 'null'){
            $value = "'$value'";
        }
        
        return $value;
    }
    
    private static function setEmptyStringAsNull(mixed $value, ?Column $columnAttribute): mixed
    {
        if(is_string($value) && empty($value)){
                if($columnAttribute && $columnAttribute->isNullable){
                  $value = 'null';    
                } else {
                  $value = $columnAttribute->defaultValue;  
                }
        }
        
        return $value;
    }
    
    private static function getIdAttribute(ReflectionProperty $entityProperty): ?Id
    {
         $idAttribute = null;
         $idAttributes = $entityProperty->getAttributes(Id::class);
         
         if(sizeof($idAttributes) > 0){
            $idAttribute = ($idAttributes[0])->newInstance();
         }
         
         return $idAttribute;
    }
    
    public static function dataBaseValueToPropertyValue(ReflectionProperty $property, ?string $value): mixed
    {
        if(!in_array($property->getType(), ['string', '?string'])){
            if(!$value && !is_numeric($value)){
                if(in_array($property->getType(), ['array', '?array'])){
                    return [];
                } else {
                    return null;
                }
            }
        }
        
        switch($property->getType()){
            case '?int':
            case 'int':
                return intval($value);
            case '?DateTimeImmutable':
            case 'DateTimeImmutable':
                return DateTimeImmutable::createFromFormat(CommonConstants::SQL_DATE_FORMAT, $value);
            case 'array':
            case '?array':
                $columnAttribute = ColumnUtils::getColumnAttribute($property);
                $arrayType = $columnAttribute->type;
                $strArray = explode(',', $value);
                switch ($arrayType){
                    case 'integer':
                        return array_map(fn($str) => intval($str), $strArray);
                    case 'float':
                        return array_map(fn($str) => floatval($str), $strArray);
                    case 'enum':
                        return array_map(fn($str) => $columnAttribute->arrayEnumClass::tryFrom($str), $strArray);
                    default:
                        return $strArray;
                }
            default:
                $propertyType = str_replace('?', '', $property->getType());
            
                if(enum_exists($propertyType)){
                    return $propertyType::tryFrom($value);
                } else {
                    return $value;
                }
        }
    }
}
