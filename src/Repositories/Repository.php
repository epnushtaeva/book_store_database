<?php
namespace App\Repositories;

use PDO;
use App\Repositories\Utils\{SqlUtils, EntityUtils, ColumnUtils};
use App\Models\{Author, Reader, Book, BookToReader};

abstract class Repository {
    protected string $entityClass = '';
    protected array $context = [];
    private ?PDO $connection = null;
    
    public function setContext(array $context): void
    {
        $this->context = $context;
    }
    
    public function findAll(): array
    {
        return $this->selectAll(SqlUtils::getSelectSql($this->entityClass, []));
    }
    
    public function findByFilter(array $filter): array
    {
         return $this->selectAll(SqlUtils::getSelectSql($this->entityClass, $filter));
    }
    
    public function remove(?int $entityId): void
    {
         if(!$entityId){
             return;
         }
         
         $this->executeNonSelectQuery(SqlUtils::getDeleteSql($this->entityClass, ['id' => $entityId]));
    }
    
    public function removeByFilter(array $filter): void
    {
          $this->executeNonSelectQuery(SqlUtils::getDeleteSql($this->entityClass, $filter));
    }
    
    protected function findEntityById(?int $entityId): ?object
    {
        if(!$entityId){
            return null;
        }
        
        $entities = $this->selectAll(SqlUtils::getSelectSql($this->entityClass, ['id' => $entityId]));
        
        if (sizeof($entities) == 0){
            return null;
        }
        
        return $entities[0];
    }
    
    protected function findEntityByFilter(array $filter): ?object
    {
        $entities = $this->selectAll(SqlUtils::getSelectSql($this->entityClass, $filter));
        
        if (sizeof($entities) == 0){
            return null;
        }
        
        return $entities[0];
    }
    
    protected function saveEntity(?object $entity): ?object
    {
        if(!$entity){
            return null;
        }
        
        $entity = EntityUtils::proxyToEntity($entity, $this->entityClass.'Proxy');
        
        $this->saveJoinedEntities($entity);
        $entityExists = $this->findEntityById($entity->getId());
        
        if(!$entityExists){
            $this->executeNonSelectQuery(SqlUtils::getInsertSql($this->entityClass, $entity));
            $entity->setId($this->connection->lastInsertId());
        } else {
            $this->executeNonSelectQuery(SqlUtils::getUpdateSql($this->entityClass, $entity));
        }
        
        return $entity;  
    }
    
    public function existsById(?int $id): bool
    {
        $this->initDatabaseConnection();
        return !!$this->findEntityById($id);
    }
    
    private function selectAll(string $query): array
    {
         $this->initDatabaseConnection();
         echo "\n$query\n";
         $query = $this->connection->query($query);
         return EntityUtils::rowsToEntities($query->fetchAll(PDO::FETCH_OBJ), $this->entityClass, $this->context);
    }
    
    private function executeNonSelectQuery(string $query): void
    {
        echo "\n$query\n";
        $this->initDatabaseConnection();
        $this->connection->exec($query);
    }
    
    private function initDatabaseConnection(): void
    {
        if($this->connection){
            return;
        }
        
        $settings = parse_ini_file('settings.ini');
        $this->connection = new PDO(
                $this->getDataBaseDsn($settings), 
                $settings['DATABASE_USERNAME'], 
                $settings['DATABASE_PASSWORD']
        );
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function getDataBaseDsn(array $settings): string
    {
        return "{$settings['DATABASE_TYPE']}:host={$settings['DATABASE_HOST']};dbname={$settings['DATABASE_DBNAME']};charset=utf8mb4";
    }
    
    private function saveJoinedEntities(object $entity): void
    {
        $propertiesForSave = ColumnUtils::getJoinedEntitiesForSave($this->entityClass, $entity);
        
        foreach($propertiesForSave as $propertyWithValue){
                $property = $propertyWithValue['property'];
                $value = $propertyWithValue['value'];
                $property->setValue(
                            $entity, 
                            $this->saveJoinedEntityIfNotExists($property->getType(), $value)
                );
        }
    }

    private function saveJoinedEntityIfNotExists(string $joinedEntityClass, ?object $entity): ?object
    {
        if(!$entity){
            return $entity;
        }
        
        $joinedEntityClass = str_replace('?', '', $joinedEntityClass);
        $entityRepository = $this->context['repositories'][$joinedEntityClass];
        
        if(!$entityRepository->existsById($entity->getId())){
            $entity = $entityRepository->save($entity);
        }
        
        return $entity;
    }
}
