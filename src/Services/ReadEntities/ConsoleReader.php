<?php

namespace App\Services\ReadEntities;

use App\Annotations\{ReadingProperty, ReadingEntity};

use App\Enums\ReaderMode;
use ReflectionClass, ReflectionProperty, DateTime;

class ConsoleReader implements Reader
{
    public function readEntity(
            string $entityClassName, 
            ReaderMode $readerMode = ReaderMode::MODE_CREATE,
             ?callable $checkEntityExistsById = null
            ): ?object 
    {
        $entityClass = new ReflectionClass($entityClassName);
        $entity = $entityClass->newInstance();
        $this->showHeaderMessage($entityClass, $readerMode);
        
        if($this->fillEntityFromConsole($entity, $entityClass, $readerMode, $checkEntityExistsById)){
           return $entity;
        } else {
           return null; 
        }
    }
    
    private function showHeaderMessage(ReflectionClass $entityClass, ReaderMode $readerMode){
        $message = "";
        
        switch ($readerMode) {
            case ReaderMode::MODE_CREATE:
                $message = 'Создание сущности "{entity_name}"';
                break;
            case ReaderMode::MODE_EDIT:
                $message = 'Редактирование сущности "{entity_name}"';
                break;
            case ReaderMode::MODE_DELETE:
                $message = 'Удаление сущности "{entity_name}"';
                break;
            case ReaderMode::MODE_VIEW:
                $message = 'Просмотр сущности "{entity_name}"';
                break;
            default:
                break;
        }
        
        $entityName = ($entityClass->getAttributes(ReadingEntity::class)[0])->newInstance()->displayName;
        
        echo str_replace("{entity_name}", $entityName, $message)."\n";
    }
    
    private function fillEntityFromConsole(object $entity, 
            ReflectionClass $entityClass, 
            ReaderMode $readerMode,
            ?callable $checkEntityExistsById = null
            ): bool
    {
        foreach($entityClass->getProperties() as $property){
            if(sizeof($property->getAttributes(ReadingProperty::class)) > 0){
                $readingPropertyMode = ($property->getAttributes(ReadingProperty::class)[0])->newInstance();
                
                if(in_array($readerMode, $readingPropertyMode->modes)){
                    $propertyValue = $this->readPropertyFromConsole($property);
                    
                    if($property->getName() == "id" && 
                            !$this->entityWithIdExists(
                                    $readerMode, 
                                    $propertyValue, 
                                    $checkEntityExistsById
                                    )){
                        return false;
                    }
                
                    
                    $property->setValue($entity, $propertyValue);
                }
            }
        }
        
        return true;
    }

    private function entityWithIdExists(
            ReaderMode $readerMode, 
            mixed $propertyValue,
            ?callable $checkEntityExistsById = null
            ): bool
    {
        if($readerMode!=ReaderMode::MODE_CREATE){
                        if($checkEntityExistsById){
                            if(!$checkEntityExistsById($propertyValue)){
                                echo PHP_EOL.'Сущность с указанным id не существует.'.PHP_EOL;
                                return false;
                            }
                        }
        }
        
        return true;
    }
    private function readPropertyFromConsole(ReflectionProperty $property): mixed
    {
        $propertyConsoleSettings = ($property->getAttributes(ReadingProperty::class)[0])->newInstance();
        $textForConsole = $this->getTextForShowInConsole($propertyConsoleSettings, $property->getName()).PHP_EOL;
        //echo $textForConsole;
        $valueError = '';
        
        do{
            if($valueError){
              echo $valueError."\n";
            }
            
            echo $textForConsole;
            
            $value = readline();
            
            if($value){
                $value = trim($value);
            }
            
            $valueError = $this->checkPropertyValue($property, $propertyConsoleSettings, $value);
        } while($valueError);
        
        
        return $this->convertValueToTargetType($property, $value);
    }
    
    private function convertValueToTargetType(ReflectionProperty $property, string $value): mixed
    {
         switch($property->getType()){
            case 'int':
                return intval($value);
            case '?int':
                return intval($value);
            default:
                return $value;
        }
    }
    
    
    private function getTextForShowInConsole(ReadingProperty $consoleSettings, string $propertyName): string
    {
         $textForConsole  = 'Пожалуйста, введите значение для поля "'.$consoleSettings->displayName.'"';
         
         if($consoleSettings->allowedValuesPresenterClass && $consoleSettings->showAllowedValues){
             $allowedValues = $consoleSettings->allowedValuesPresenter->getAllowedValues($propertyName);

             if($consoleSettings->isArrayInAllowed){
                 $textForConsole = str_replace('значение', 'значения через запятую', $textForConsole);
             }
             
             $textForConsole .= '(Допустимые значения для данного поля: '. 
                     $this->getAllowedValuesText($allowedValues).
                     '. Внимание! Если в значении присутствует знак  -, то нужно вводить текст до сочетания " -", до пробела и черты!)';
         } else {
             $additionalText = [];
             
             if(!$consoleSettings->isRequired){
                 $additionalText[] = '*Необязательное значение';
             }
             
             if($consoleSettings->isMoreThanZero){
                 $additionalText[] = 'Значение должно быть больше нуля';
             }
             
             if($consoleSettings->isDate){
                 $additionalText[] = 'Введите дату в формате 01.02.2003';
             }
             
             if($additionalText){
                 $textForConsole .= ' ('.implode(', ', $additionalText).')';
             }
         }
         
         return $textForConsole.": ";
    }
    
    private function getAllowedValuesText(array $allowedValues): string
    {
         return implode(', ', array_map(fn($value) => $this->getAllowedValueText($value), $allowedValues));
    }
    
    private function getAllowedValueText(array $allowedValue): string
    {
        return $allowedValue['text']?($allowedValue['value'].' - '.$allowedValue['text']):$allowedValue['value'];
    }
    
    private function checkPropertyValue(
            ReflectionProperty $property, 
            ReadingProperty $propertySettings, 
            string $value
    ): string
    {
        if($propertySettings->allowedValuesPresenterClass){
              $allowedValues = $propertySettings->allowedValuesPresenter->getAllowedValues($property->getName());
              return $this->checkAllowedFieldValue(
                      $value, 
                      $propertySettings->displayName, 
                      $allowedValues, 
                      $propertySettings->showAllowedValues,
                      $propertySettings->isArrayInAllowed
                      );
        }
        
        
        if($propertySettings->isDate){
            return $this->checkDateFieldValue(
                        $value, 
                        $propertySettings->displayName, 
                        $propertySettings->isRequired
                    );
        }

        switch($property->getType()){
            case 'int':
                return $this->checkNumberFieldValue(
                            $value, 
                            $propertySettings->displayName, 
                            $propertySettings->isMoreThanZero
                        );
            case '?int':
                return $this->checkNumberFieldValue(
                            $value, 
                            $propertySettings->displayName, 
                            $propertySettings->isMoreThanZero
                        );
            case 'string':
                return $this->checkStringFieldValue(
                            $value, 
                            $propertySettings->displayName, 
                            $propertySettings->isRequired
                        );
            case '?string':
                return $this->checkStringFieldValue(
                            $value, 
                            $propertySettings->displayName, 
                            $propertySettings->isRequired
                        );
            default:
                return '';
        }
    }
    
    private function checkAllowedFieldValue(string $value, 
                string $fieldName, 
                array $allowedValues, 
                bool $showAllowedValues,
                bool $isArrayInAllowed
            ){
                $allowedValues = array_column($allowedValues, 'value');
                $message = '';
                
                if($isArrayInAllowed){
                    $values = explode(',', $value);
                } else {
                    $values = [$value];
                }
                
                $unrightValues = [];
                
                foreach($values as $currentValue){
                       if(!in_array($currentValue, $allowedValues)){
                           array_push($unrightValues, $currentValue);
                       }
                }
                
                if(sizeof($unrightValues) > 0){
                        if($isArrayInAllowed){
                            $message = "Значения ".implode('', $unrightValues)." недопустимы для поля \"$fieldName\".";
                        } else {
                            $message = "Поле \"$fieldName\" имеет недопустимое значение.";
                        }

                        if($showAllowedValues){
                               $message .= " (Допустимые значения: ".implode(', ', $allowedValues).")";
                        } else {
                               $message .= " Возможно, в базе отсутствует связанная сущность с таким идентификатором.";
                        }
                }
                            
                return $message;
    }
    
    private function checkNumberFieldValue(string $value, string $fieldName, bool $isMoreThanZero):string
    {
        if(!is_numeric($value)){
            return "Поле \"$fieldName\" должно быть корректным числом";
        }
        
        if(($isMoreThanZero && floatval($value) <= 0)){
            return "Поле \"$fieldName\" должно быть больше нуля";
        }
        
        return '';
    }
    
    private function checkStringFieldValue(string $value, string $fieldName, bool $isRequired):string
    {
        if($isRequired && !$value){
            return "Поле \"$fieldName\" должно иметь непустое значение";
        }
        
        return '';
    }
    
    private function checkDateFieldValue(string $value, string $fieldName, bool $isRequired):string
    {
        if($isRequired && !$value){
            return "Поле \"$fieldName\" должно иметь непустое значение";
        }
        
        if(trim($value)){
             $dateObj = DateTime::createFromFormat('d.m.Y', $value);
             if(!($dateObj && $dateObj->format('d.m.Y') === $value)){
                 return "Поле \"$fieldName\" должно иметь значение в виде даты формата 01.03.2003";
             }
        }
        
        return '';
    }
}
