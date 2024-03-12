<?php

namespace demo\controllers;

use demo\core\Request;
use demo\core\Response;
use demo\services\DemoService;

class DemoController
{
    private $demoService;
    public function __construct(DemoService $demoService)
    {
        $this->demoService = $demoService;
    }

    public function index(Request $request, Response $response)
    {
        return $response->json($this->demoService->getData());
    }
}