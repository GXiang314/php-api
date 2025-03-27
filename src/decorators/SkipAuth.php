<?php

namespace demo\decorators;

use Attribute;

#[Attribute]
class SkipAuth
{
    public function __construct(private bool $isPublic = true)
    {
    }

    public function getIsPublic(): bool
    {
        return $this->isPublic;
    }
}