<?php
namespace App\Repositories\Utils;

use App\Models\{Author, Book, BookToReader, Reader};

class LazyLoadingUtils 
{
    public static function makeProxyClass(string $className): void
    {
        $classNameParts = explode('\\', $className);
        $extendsClassName = end($classNameParts);
        $proxyClassName = "{$extendsClassName}Proxy";
        $nameSpaceName = str_replace('?', '', implode('\\', array_slice(explode('\\', $className), 0, -1)));

        if(!class_exists($className.'Proxy')){
            $lazyFunctions = self::getLazyFunctionsForProxy($className);
            eval("namespace $nameSpaceName; class $proxyClassName extends  $extendsClassName{ public function __construct(private array \$context, private array \$entitiesIds){} public function addJoinEntityId(string \$entityClass, int \$id){\$this->entitiesIds[\$entityClass] = \$id;} $lazyFunctions}");
        }
    }
    
    private static function getLazyFunctionsForProxy(string $className): string
    {
        $functions = [];
        $lazyLoadProperties = ColumnUtils::getLazyLoadColumns($className);
        foreach($lazyLoadProperties as $propertyName => $propertyClass){
                $functions[] = self::getGetterFunctionForProperty($propertyName,  $propertyClass);
        }
        
        echo implode(' ', $functions);
        return implode(' ', $functions);
    }
    
    private static function getGetterFunctionForProperty(string $propertyName, string $propertyClass): string
    {
        $classNameParts = explode('\\', $propertyClass);
        $extendsClassName = end($classNameParts);
        $propertyNameForGettersAndSetters = ucfirst($propertyName);
        $propertyGetterName = "get$propertyNameForGettersAndSetters";
        $propertySetterName = "set$propertyNameForGettersAndSetters";
        return " public function $propertyGetterName(): $extendsClassName{"
                        . "if(!parent::$propertyGetterName()){"
                        . " \$this->$propertySetterName("
                        . "      \$this->context['repositories']['$propertyClass']->find(\$this->entitiesIds['$propertyClass'])"
                        . " );"
                        . "}"
                        . "return parent::$propertyGetterName();"
                        . "}";
    }
}
