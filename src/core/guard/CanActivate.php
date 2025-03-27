<?php

namespace demo\core\guard;

use demo\core\http\ExecutionContext;

interface CanActivate
{
    /**
     * Check if the request can be activated
     * @return bool
     */
    public function canActivate(ExecutionContext $executionContext): bool;
}