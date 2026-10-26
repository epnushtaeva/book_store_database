<?php

namespace App\Repositories\Utils;
use ReflectionClass;

abstract class EntityUtils 
{
   public static function rowsToEntities(array $rows, string $entityClass, array $context): array
   {
       return array_map(fn($row) => self::rowToEntity($row, $entityClass, $context), $rows);
   }
   
   public static function rowToEntity(object $row, string $entityClass, array $context): mixed
   {
       LazyLoadingUtils::makeProxyClass($entityClass);
       $entityClassProxy = new ReflectionClass($entityClass.'Proxy');
       $entity = $entityClassProxy->newInstanceArgs(['context' => $context, 'entitiesIds' => []]);
       
       foreach((new ReflectionClass($entityClass))->getProperties() as $property){
           $rowColumnName = ColumnUtils::getColumnName($property);
           
           if($rowColumnName){
                $joinColumnAttribute = ColumnUtils::getJoinColumnAttribute($property);
                
                if($joinColumnAttribute){
                    $value = $row->$rowColumnName;
                    
                    if(!$value){
                        $value = null;
                    } else {
                        $value = intval($value);
                    }
                    
                    $entity->addJoinEntityId(str_replace('?', '', $property->getType()), $value);
                } else {
                    $setterName = 'set'.ucfirst($property->getName());
                    $entity->$setterName(ValueUtils::dataBaseValueToPropertyValue($property, $row->$rowColumnName));
               };
           }
       }
       
       return $entity;
   }
   
   public static function proxyToEntity(object $proxy, string $proxyClass): mixed
   {
       LazyLoadingUtils::makeProxyClass(str_replace('Proxy', '', $proxyClass));
       
       if ($proxy instanceof $proxyClass){
           $entityClass = str_replace('Proxy', '', $proxyClass);
           $entityClass = new ReflectionClass($entityClass);
           $entity = $entityClass->newInstance();
           
           foreach($entityClass->getProperties() as $entityProperty){
               $getterName = 'get'.ucfirst($entityProperty->getName());
               $setterName = 'set'.ucfirst($entityProperty->getName());
               $entity->$setterName($proxy->$getterName());
           }
           
           return $entity;
       } 
       
       return $proxy;
   }
}
