<?php

namespace demo\modules\demo;

use demo\core\Request;
use demo\core\Response;
use demo\decorators\SkipAuth;

#[SkipAuth()]
class DemoController
{
    private $demoService;
    public function __construct(DemoService $demoService)
    {
        $this->demoService = $demoService;
    }

    public function index(Request $request, Response $response)
    {
        return $this->demoService->getUsers();
    }

    public function create(Request $request, Response $response)
    {
        if (empty($request->body["username"])) {
            throw new \Exception("Username cannot be empty", 400);
        }
        return [
            "username" => $request->body["username"]
        ];
    }
}