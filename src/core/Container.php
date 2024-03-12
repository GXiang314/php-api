<?php
namespace demo\core;

class Container
{
    /* #region 實作 DI Instance */
    public function getInstance($class_name)
    {
        //取得類別反射
        $reflector = new \ReflectionClass($class_name);
        //取得該類別建構子
        $constructor = $reflector->getConstructor();
        //取得建構子參數
        $di_params = [];
        if ($constructor) {
            foreach ($constructor->getParameters() as $param) {
                $class = $param->getType();
                // if is class then create instance
                if (!$class->isBuiltin()) {
                    $di_params[] = $this->getInstance($class->getName());
                } else {
                    $di_params[] = $param;
                }
            }
        }
        return $reflector->newInstanceArgs($di_params);
    }
    /* #endregion */
}