<?php

namespace App\Repositories\Utils;
use ReflectionClass, ReflectionProperty;
use App\Annotations\Entity;
use App\Models\{Author, Book, Reader, BookToReader};

abstract class SqlUtils 
{
    public static function getDeleteSql(string $entityClass, array $whereArray): string
    {
        $tableName = self::getTableName($entityClass);
        $joins = self::getJoinString($entityClass, $whereArray);
        $where = self::getWhereSql($entityClass, $whereArray);
        return "DELETE t FROM $tableName t  $joins $where";
    }
    
    public static function getSelectSql(string $entityClass, array $whereArray): string
    {
        $tableName = self::getTableName($entityClass);
        $joins = self::getJoinString($entityClass, $whereArray);
        $where = self::getWhereSql($entityClass, $whereArray);
        return "SELECT t.* FROM $tableName t $joins $where";
    }
    
    public static function getUpdateSql(string $entityClass, object $entity): string
    {
        $tableName = self::getTableName($entityClass);
        $columnsAndValues = self::getUpdateColumnsAndValuesSql($entityClass, $entity);
        $idSql = ValueUtils::getIdSql($entityClass, $entity);
        return "UPDATE $tableName SET $columnsAndValues WHERE $idSql";
    }
    
    private static function getUpdateColumnsAndValuesSql(string $entityClass, object $entity): string
    {
        $columns = ColumnUtils::getColumns($entityClass);
        $values = ValueUtils::getValues($entityClass, $entity);
        return implode(',', array_map(fn($column, $value) => "$column = $value", $columns, $values));
    }
    
    public static function getInsertSql(string $entityClass, object $entity): string
    {
        $tableName = self::getTableName($entityClass);
        $columns = self::getColumnsForInsertSql($entityClass);
        $values = self::getValuesForInsertSql($entityClass, $entity);
        return "INSERT INTO $tableName $columns $values";
    }
    
    private static function getValuesForInsertSql(string $entityClass, object $entity): string
    {
         return 'VALUES('.implode(',', ValueUtils::getValues($entityClass, $entity)).')';
    }
    
    private static function getColumnsForInsertSql(string $entityClass): string
    {
        return '('.implode(',', ColumnUtils::getColumns($entityClass)).')';
    }
    
    private static function getTableName(string $entityClass): string
    {
        $class = new ReflectionClass($entityClass);
        return ($class->getAttributes(Entity::class)[0])->newInstance()->tableName;
    }
    
    private static function getWhereSql(string $entityClass, array $whereArray): string
    {
       $class = new ReflectionClass($entityClass);
       $resultArray = [];
       $joinInfo = self::getJoinInfoArray($class, $whereArray);
       
       foreach($whereArray as $property => $value){
            $propertyNameParts = explode('.', $property);
            $propertyName = end($propertyNameParts);
            $tableAlias = implode('.', array_slice(explode('.', $property), 0, -1));
            
            if(!$tableAlias){
                $tableAlias = 'main_table1';
            }
            
            $tableAlias = $joinInfo[$tableAlias]['alias'];
            
            $entityProperty = $class->getProperty($propertyName);
            $columnName = $tableAlias.'.'.ColumnUtils::getColumnName($entityProperty);
            
            if($columnName){
               $resultArray []= self::columnAndValueToWhereStr($columnName, $entityProperty, $value);
            }
        } 
        
        if($resultArray){
            return "WHERE ".implode(' AND ', $resultArray);
        } else {
            return '';
        }
    }
    
    private static function getTargetProperty(ReflectionClass $entityClass, array $propertyNameParts): ReflectionProperty
    {
        $currentProperty = null;
        
        foreach($propertyNameParts as $currentPropertyName){
            $currentProperty = $entityClass->getProperty($currentPropertyName);
            $currentPropertyType = str_replace('?', '', $currentProperty->getType());
            echo $currentPropertyType."\n";
            
            if(class_exists($currentPropertyType) && !enum_exists($currentPropertyType)){
                $entityClass = new ReflectionClass($currentPropertyType);
            }
        }
        
        return $currentProperty;
    }
    
    private static function columnAndValueToWhereStr(
                                      string $columnName,
                                      ReflectionProperty $entityProperty,
                                      mixed $value
    ): string
    {
         $columnAttribute = ColumnUtils::getColumnAttribute($entityProperty);
         
         if($columnAttribute){
                $value = ValueUtils::formatValueForColumn(
                               $entityProperty, 
                               $columnAttribute, 
                               $value
               );
         } else {
               if(!$value && $value !== 0){
                   $value = null;
               }
         }
                
         if($value != 'null'){
                   return "$columnName = $value";
         } else {
                   return "$columnName IS NULL";
         }
    }
    
    private static function getJoinString(string $entityClass, array $filters): string
    {
        $entityClass = new ReflectionClass($entityClass);
        $filterNamesToJoinInfo = self::getJoinInfoArray($entityClass, $filters);
        $joinStr = '';
        
        foreach($filterNamesToJoinInfo as $table => $joinInfo){
            if($table != 'main_table1'){
                $joinStr .= " LEFT JOIN {$joinInfo['table']} {$joinInfo['alias']} ON {$joinInfo['alias']}.id = {$joinInfo['join_column']} ";
            }
        }
        
        return $joinStr;
    }
    
    private static function getJoinInfoArray(ReflectionClass $entityClass, array $filters): array
    {
        $filterNamesToJoinInfo = ['main_table1' => ['alias' => 't']];
        $filterNames = array_keys($filters);
        
        foreach($filterNames as $filterName){
            self::addJoinInfo($filterName, $entityClass, $filterNamesToJoinInfo);
        }
        
        return $filterNamesToJoinInfo;
    }
    
    private static function addJoinInfo(
            string $filterName, 
            ReflectionClass $entityClass, 
            array &$filterNamesToJoinInfo
    ): void
    {
            $joinParts = self::getJoinParts($filterName);
            
            if(sizeof($joinParts) == 0){
                return;
            }
            
            $lastFilterParts = '';
            $currentMainEntityClass = $entityClass;
             
            foreach ($joinParts as $filterPart){
                $currentFilterPart = ltrim($lastFilterParts.'.'.$filterPart,'.');
                
                if(!array_key_exists($currentFilterPart,  $filterNamesToJoinInfo)){
                    $filterNamesToJoinInfo[$currentFilterPart] = self::getJoinPropertyInfo(
                            $filterPart,
                            $lastFilterParts,
                            $filterNamesToJoinInfo,
                            $currentMainEntityClass
                    );
                }
                
                $lastFilterParts = $currentFilterPart;
                $currentMainEntityClass = new ReflectionClass(
                            str_replace(
                                    '?', 
                                    '', 
                                    $currentMainEntityClass->getProperty($filterPart)->getType()
                            )
                        );
            }
    }
    
    private static function getJoinPropertyInfo(
            string $currentFilterPart,
            string $lastFilterParts,
            array $filterNamesToJoinInfo,
            ReflectionClass $currentEntityClass
    ): array
    {
         $currentJoinProperty = $currentEntityClass->getProperty($currentFilterPart);
         $currentJoinPropertyTableName = self::getTableName(str_replace('?', '', $currentJoinProperty ->getType()));
         $alias = 't';
         
         if(array_key_exists($lastFilterParts, $filterNamesToJoinInfo)){
             $alias = $filterNamesToJoinInfo[$lastFilterParts]['alias'];
         }
         
         return [
             'table' => $currentJoinPropertyTableName, 
             'alias' => 't'.sizeof(array_keys($filterNamesToJoinInfo)),
             'join_column' => $alias.
                                            '.'.
                                            ColumnUtils::getJoinColumnAttribute($currentJoinProperty)->name
         ];
    }
    
    private static function getJoinParts(string $filterName): array
    {
        $joinParts = explode('.', $filterName);
        array_pop($joinParts);
        return $joinParts;
    }
}
