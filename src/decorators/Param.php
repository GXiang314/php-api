<?php

namespace demo\decorators;

use Attribute;

#[Attribute]
class Param
{
    public function __construct(private $filterAttr = null)
    {
    }

    public function getFilterAttr()
    {
        return $this->filterAttr;
    }
}
