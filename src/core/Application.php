<?php
namespace demo\core;
use demo\core\guard\CanActivate;
use demo\core\Request;
use demo\core\Response;
use demo\core\Router;
use demo\core\Container;

class Application
{
    public Router $router;
    private Request $request;
    private Response $response;
    private Container $container;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->container = new Container();
        $this->router = new Router($this->request, $this->response, $this->container);
    }

    public function use(mixed $handler)
    {
        $handlerInstance = $this->container->getInstance($handler);
        if ($handlerInstance instanceof CanActivate) {
            $this->router->registerGuard($handlerInstance);
        }
    }

    public function cors(
        $allowOrigins = ["*"],
        $allowMethods = ["GET", "POST", "PUT", "DELETE", "OPTIONS"],
        $allowHeaders = ["Content-Type", "Authorization"]
    )
    {
        header("Access-Control-Allow-Origin: " . implode(", ", $allowOrigins));
        header("Access-Control-Allow-Methods: " . implode(", ", $allowMethods));
        header("Access-Control-Allow-Headers: " . implode(", ", $allowHeaders));

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit(0);
        }
    }

    public function run()
    {
        $this->router->resolve();
    }
}