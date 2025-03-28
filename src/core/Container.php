<?php
namespace demo\core;

use demo\decorators\Singleton;
use Exception;
use ReflectionClass;
use ReflectionNamedType;

class Container
{
    private static $reflectionCache = [];

    private $singletonCache = [];

    public static function getReflection($class_name)
    {
        return self::$reflectionCache[$class_name] ?? null;
    }

    /* #region 實作 DI Instance */
    public function getInstance($class_name)
    {
        // 利用快取加快 ReflectionClass 建立
        if (self::getReflection($class_name) === null) {
            self::$reflectionCache[$class_name] = new ReflectionClass($class_name);
        }
        $reflector = self::getReflection($class_name);

        $constructor = $reflector->getConstructor();
        $di_params = [];
        if ($constructor) {
            foreach ($constructor->getParameters() as $param) {
                $type = $param->getType();
                if ($type instanceof ReflectionNamedType) {
                    if (!$type->isBuiltin()) {
                        $di_params[] = $this->getInstance($type->getName());
                    } else {
                        if ($param->isDefaultValueAvailable()) {
                            $di_params[] = $param->getDefaultValue();
                        } else {
                            throw new Exception("無法解析內建型態參數: " . $param->getName());
                        }
                    }
                } else {
                    throw new Exception("不支援的參數型態: " . $param->getName());
                }
            }
        }
        if ($instance = $this->handleSingleton($class_name, $reflector, $di_params)) {
            return $instance;
        }

        return $reflector->newInstanceArgs($di_params);
    }
    /* #endregion */

    private function handleSingleton($class_name, ReflectionClass $reflector, array $di_params)
    {
        $singleton = $reflector->getAttributes(Singleton::class);
        if (count($singleton) > 0) {
            if (isset($this->singletonCache[$class_name])) {
                return $this->singletonCache[$class_name];
            }
            $instance = $reflector->newInstanceArgs($di_params);
            $this->singletonCache[$class_name] = $instance;
            return $instance;
        }
        return null;
    }
}
