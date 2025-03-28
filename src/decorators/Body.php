<?php

namespace demo\decorators;

use Attribute;

#[Attribute]
class Body
{

    public function __construct(private $filterAttr = null)
    {
    }

    public function getFilterAttr()
    {
        return $this->filterAttr;
    }
}
