<?php

namespace App\Repositories;

trait RepositoryTrait 
{
    public function getNextId(array $objects): int
    {
        $maxId = 0;
        
        foreach ($objects as $object){
            if($object->getId() > $maxId){
                $maxId = $object->getId();
            }
        }
        
        return $maxId + 1;
    }
    
    public function checkFilter(array $filter, object $object): bool
    {
        return array_any($filter, fn($filterValue, $filterName) => !($this->getPropertyFromObject($filterName, $object) == $filterValue));
    }
    
    public function getPropertyFromObject(string $propertyName, object $object): mixed
    {
        $properties = explode('.', $propertyName);
        $result = '';
        
        foreach($properties as $property){
            $getter = 'get'.ucfirst($property);
            
            if(!$result){
                $result =  $object?->{$getter}();
            } else {
                $result = $result?->{$getter}();
            }
        }
        
        return $result;
    }   
}
