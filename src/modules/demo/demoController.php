<?php

namespace demo\modules\demo;

use demo\core\Request;
use demo\core\Response;

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

    public function create(Request $request, Response $response)
    {
        return $response->json($request->getBody());
    }
}