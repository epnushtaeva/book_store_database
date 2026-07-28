<?php

namespace App\Annotations;

use App\Fabrics\ServiceFabric;
use Attribute;
use ReflectionClass, ReflectionMethod;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ReadingProperty 
{   
    public GetAllowedValuesInterface $allowedValuesPresenter;
    
    public function __construct(
            public string $displayName,
            public array $modes,
            public bool $isRequired = true,
            public bool $isMoreThanZero = false,
            public string $allowedValuesPresenterClass = '',
            public bool $showAllowedValues = false,
            public bool $isDate = false,
            public bool $isArrayInAllowed = false
            
    ) {
        if($allowedValuesPresenterClass){
            $this->loadAllowedValuesPresenterFromFabric($allowedValuesPresenterClass);
        }
    }
    
    private function loadAllowedValuesPresenterFromFabric(string $allowedValuesPresenterClass): void
    {
        $getPresenterMethod = $this->getMethodFromFabricByReturn($allowedValuesPresenterClass);
        $this->allowedValuesPresenter = $getPresenterMethod->invoke(null);
    }
    
    private function getMethodFromFabricByReturn(string $returningClass): ?ReflectionMethod
    {
        $fabricClass = new ReflectionClass(ServiceFabric::class);
        
        foreach($fabricClass->getMethods(ReflectionMethod::IS_STATIC) as $method){
            if($method->getReturnType()->getName() == $returningClass){
                return $method;
            }
        }
        
        return null;
    }
}
