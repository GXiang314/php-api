<?php

namespace demo\decorators;

use Attribute;

#[Attribute]
class Roles
{
    public function __construct(private array $roles = [])
    {
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}