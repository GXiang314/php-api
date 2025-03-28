<?php

namespace demo\modules\demo;

use demo\decorators\Body;
use demo\decorators\SkipAuth;

#[SkipAuth()]
class DemoController
{
    private $demoService;
    public function __construct(DemoService $demoService)
    {
        $this->demoService = $demoService;
    }

    public function index()
    {
        return $this->demoService->getUsers();
    }

    public function create(#[Body] CreateUserInputRequest $body)
    {
        if (empty($body->username)) {
            throw new \Exception("Username cannot be empty", 400);
        }
        return [
            "username" => $body->username
        ];
    }
}

class CreateUserInputRequest
{
    public string $username;
}