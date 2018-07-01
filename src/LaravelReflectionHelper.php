<?php
/**
 * Created by PhpStorm.
 * User: alive
 * Date: 12/15/17
 * Time: 10:35 AM
 */

namespace App\Resources\Reflection;


use ReflectionClass;

class LaravelReflectionHelper
{

    protected $relatedClassNames = [
        'BelongsTo',
        'BelongsToMany',
        'HasOne',
        'HasMany',
    ];

    public function getClassRelationMethodsNames($class)
    {
        $reflection = new ReflectionClass ( $class );
//        $temp = new \ReflectionMethod($class, 'user');
//        return $temp;
        $thisClassMethods = [];
        if ( is_object($reflection) ) {
            $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method) {
                $classMethod = collect($method)->get('class');
                if ($classMethod == get_class($class)) {
                    if($method->getNumberOfParameters()==0){
                        $className = get_class($class);
                        $methodName = collect($method)->get('name');
                        if (gettype((new $className())->$methodName())=='object' ) {
                            $outputClassName = collect(explode('\\', get_class((new $className())->$methodName())))->last();
                            if (is_numeric(array_search($outputClassName,$this->relatedClassNames))){
                                array_push($thisClassMethods,$methodName);
                            }
                        }
                    }
                }
            }
        }
        return $thisClassMethods;
    }

    public function getClassMethodsNames($class,$filter = null)
    {
        $reflection = new ReflectionClass ( $class );
        $thisClassMethods = [];
        if ( is_object($reflection) ) {
            $methods = is_null($filter)? $reflection->getMethods(): $reflection->getMethods($filter);
            foreach ($methods as $method) {
                $classMethod = collect($method)->get('class');
                if ($classMethod == get_class($class)) {
                    array_push($thisClassMethods,collect($method)->get('name'));
                }
            }
        }
        return $thisClassMethods;
    }

}