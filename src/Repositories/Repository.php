<?php
namespace App\Repositories;


abstract class Repository {
    use RepositoryTrait;
    
    private array $entities = [];

    public function findAll(): array
    {
        return $this->entities;
    }
    
    public function findByFilter(array $filter): array
    {
         return array_filter($this->entities, fn($targetEntity) => !$this->checkFilter($filter, $targetEntity));
    }
    
    public function remove(?int $entityId): void
    {
         if(!$entityId){
             return;
         }
         
         $entityIndex = array_find_key($this->entities, fn($targetEntity) => $targetEntity->getId() == $entityId);
         
         if($entityIndex !== null){
            array_splice($this->entities, $entityIndex, 1);
         }
    }
    
    public function removeByFilter(array $filter): void
    {
         $entityIndex = 1;
         
         while($entityIndex !== null){
                $entityIndex = array_find_key($this->entities, fn($targetEntity) => !$this->checkFilter($filter, $targetEntity));

                if($entityIndex !== null){
                   array_splice($this->entities, $entityIndex, 1);
                }
         }
    }
    
    protected function findEntityById(?int $entityId): ?object
    {
        if(!$entityId){
            return null;
        }
        
        return array_find($this->entities, fn($targetEntity) => $targetEntity->getId() == $entityId);
    }
    
    protected function findEntityByFilter(array $filter): ?object
    {
       return array_find($this->entities, fn($targetEntity) => $this->checkFilter($filter, $targetEntity));
    }
    
    protected function saveEntity(?object $entity): ?object
    {
        if(!$entity){
            return null;
        }
        
        $entityIndex = array_find_key($this->entities, fn($targetEntity) => $targetEntity->getId() == $entity->getId());
        
        if($entityIndex !== null){
            $this->entities[$entityIndex] = $entity;
        } else {
            $entity->setId($this->getNextId($this->entities));
            array_push($this->entities, $entity);
        }
        
        return $entity;  
    }
    
    public function existsById(?int $id): bool
    {
        return array_any($this->entities, fn($entity) => $entity->getId() == $id);
    }
}
