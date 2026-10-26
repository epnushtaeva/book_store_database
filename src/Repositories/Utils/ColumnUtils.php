<?php

namespace App\Repositories\Utils;

use App\Constants\SqlConstants;
use ReflectionProperty, ReflectionClass, DateTimeImmutable;
use App\Annotations\{Column, Id, JoinColumn};
use App\Utils\StringUtils;

abstract class ColumnUtils 
{
    public static function getColumns(string $entityClass): array
    {
        $columns = [];
        $class = new ReflectionClass($entityClass);
        
        foreach($class->getProperties() as $property){
            $columnName = self::getColumnName($property);
            
            if($columnName && $columnName != 'id'){
                $columns []= $columnName;
            }
        }
        
        return $columns;
    }
    
    public static function getJoinedEntitiesForSave(string $savingEntityClass, object $entity): array
    {
        $propertiesForSave = [];
        $entityClass = new ReflectionClass($savingEntityClass);
        
        foreach($entityClass->getProperties() as $property){
            $isOtherJoinedEntity = self::getJoinColumnAttribute($property);
            
            if($isOtherJoinedEntity){
                $propertiesForSave[] = ['property' => $property, 'value' => $property->getValue($entity)];
            }
        }
        
        return $propertiesForSave;
    }
    
    public static function getLazyLoadColumns(string $className): array
    {
         $class = new ReflectionClass($className);
         $lazyLoadProperties = [];
         
         foreach($class->getProperties() as $property){
             $joinColumnAttribute = self::getJoinColumnAttribute($property);
            
            if($joinColumnAttribute){
                $lazyLoadProperties[$property->getName()] = str_replace('?', '', $property->getType());
            }
         }
         
         return $lazyLoadProperties;
    }
    
    public static function getColumnAttribute(ReflectionProperty $entityProperty): ?Column
    {
         $columnAttribute = null;
         $columnAttributes = $entityProperty->getAttributes(Column::class);
         
         if(sizeof($columnAttributes) > 0){
            $columnAttribute = ($columnAttributes[0])->newInstance();
         }
         
         return $columnAttribute;
    }
    
    public static function getJoinColumnAttribute(ReflectionProperty $entityProperty): ?JoinColumn
    {
         $columnAttribute = null;
         $columnAttributes = $entityProperty->getAttributes(JoinColumn::class);
         
         if(sizeof($columnAttributes) > 0){
            $columnAttribute = ($columnAttributes[0])->newInstance();
         }
         
         return $columnAttribute;
    }

    
    public static function isColumnNumeric(Column $columnAttribute, string $type): bool
    {
        return $columnAttribute->type && in_array($columnAttribute->type, SqlConstants::NUMERIC_SQL_TYPES) || 
                          !$columnAttribute->type && in_array($type, SqlConstants::NUMERIC_PROPERTY_TYPES);
    }
    
    public static function isColumnDate(Column $columnAttribute, string $type): bool
    {
        return $columnAttribute->type && $columnAttribute->type==SqlConstants::TYPE_DATE || 
                !$columnAttribute->type && str_replace('?', '', $type) === 'DateTimeImmutable';
    }
    
    public static function getColumnName(ReflectionProperty $entityProperty): string
    {
       $columnAttribute = self::getColumnAttribute($entityProperty);
       $columnName = '';
       
       if(!$columnAttribute){
           $columnAttribute = self::getJoinColumnAttribute($entityProperty);
           
           if(!$columnAttribute || !$columnAttribute->name){
              $idAttributes = $entityProperty->getAttributes(Id::class);
         
              if(sizeof($idAttributes) > 0){
                  $columnName = $entityProperty->getName();
                  
                  if($columnName){
                      return StringUtils::camelCaseStringToSnakeCase($entityProperty->getName());
                  }
              }
           }
       }
       
       $columnName = $columnAttribute->name;
       
       if(!$columnName){
           $columnName = StringUtils::camelCaseStringToSnakeCase($entityProperty->getName());
       }
       
       return $columnName;
    }
}
