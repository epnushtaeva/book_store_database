<?php

namespace App\Services\WriteEntities;

use App\Annotations\{ViewProperty, ViewEntity};
use ReflectionClass;
class ConsoleWriter implements Writer
{
    
    public function writeEntities(
            array $entities, 
            string $entityClassName, 
            bool $writeInOneRow = false, 
            string $spaces =''
    ): void
    {
        if(sizeof($entities) == 0 && !$writeInOneRow){
            echo 'Список указанных сущностей пустой';
            return;
        }
        
        if($writeInOneRow){
           $this->writeEntitiesHeaders($entityClassName, $spaces);
        }
        
        foreach($entities as $entity){
            $this->writeEntity($entity, $entityClassName, $writeInOneRow, $spaces);
        }
    }

    public function writeEntity(?object $entity, string $entityClassName, bool $writeInOneRow = false, string $spaces =''): void 
    {
        if(!$entity){
            echo 'Сущность с указанным ID не существует или введены некорректные данные';
            return;
        }
        
        $entityClass = new ReflectionClass($entityClassName);
        
        if($writeInOneRow){
           echo $spaces;
        } else {
            $this->writeEntityHeader($entityClass);
        }
        
        foreach($entityClass->getProperties() as $property){
            $displayNameAttributes = $property->getAttributes(ViewProperty::class);
            
            if(sizeof($displayNameAttributes) > 0){
               $this->writeProperty(
                        ($displayNameAttributes[0])->newInstance(), 
                        $property->getValue($entity), 
                        $writeInOneRow,
                        $spaces .' '
                       );
            }
        }
        
        
        echo PHP_EOL;
    }
    
    private function writeEntityHeader(ReflectionClass $entityClass): void
    {
        $entityDisplayNameAttribute = ($entityClass->getAttributes(ViewEntity::class)[0])->newInstance();
        echo 'Сущность "'.$entityDisplayNameAttribute->displayName.'": '.PHP_EOL;
    }
    
    private function writeEntitiesHeaders(string $entityClassName, string $spaces = ''): void
    {
       echo $spaces;
       $entityClass = new ReflectionClass($entityClassName);
       
       foreach($entityClass->getProperties() as $property){
            $displayNameAttributes = $property->getAttributes(ViewProperty::class);
            
            if(sizeof($displayNameAttributes) > 0){
                echo ($displayNameAttributes[0])->newInstance()->displayName."\t";
            }
       }
       
       echo PHP_EOL;
    }


    private function writeProperty(
                ViewProperty $displayAttribute, 
                mixed $value, 
                bool $writeInOneRow = false, 
                string $spaces =''
            ): void
    {    
         if($writeInOneRow){
            $this->writeValueInOneRow($value);
         } else {
             $this->writeValueInManyRows($displayAttribute, $value, $spaces);
         }
    }
    
    private function writeValueInOneRow(mixed $value): void
    {
        if(is_null($value)){
             echo " \t";
        } else {
             echo $value."\t";
        }
    }
    
    private function writeValueInManyRows(ViewProperty $displayAttribute, mixed $value, string $spaces): void
    {
        $text = $spaces.$displayAttribute->displayName.': ';

        if(!is_array($value) && !is_null($value)){
             $text .= $value;
        }

        $text .= PHP_EOL;
        echo $text;

        if(is_array($value)){
             $this->writeEntities($value, $displayAttribute->entityClass, true, $spaces.' ');
        }
    }
}
