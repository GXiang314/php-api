<?php
namespace demo\core;
use demo\core\Request;
use demo\core\Response;
use demo\core\Router;
use demo\core\Container;

class Application {
    public Router $router;
    private Request $request;
    private Response $response;
    private Container $container;

    public function __construct(){
        $this->request = new Request();
        $this->response = new Response();
        $this->container = new Container();
        $this->router = new Router($this->request, $this->response, $this->container);
    }

    public function run() {
        $this->router->resolve();
    }
}