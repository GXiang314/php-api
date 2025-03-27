<?php
namespace demo\core;

use demo\core\guard\CanActivate;
use demo\core\http\ExecutionContext;

class Router
{
    private array $routes = [];
    private Request $request;
    private Response $response;
    private Container $container;
    private array $guards = [];

    public function __construct($request, $response, $container)
    {
        $this->request = $request;
        $this->response = $response;
        $this->container = $container;
    }

    public function get($path, $callback = null)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback = null)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function put($path, $callback = null)
    {
        $this->routes['PUT'][$path] = $callback;
    }

    public function patch($path, $callback = null)
    {
        $this->routes['PATCH'][$path] = $callback;
    }

    public function delete($path, $callback = null)
    {
        $this->routes['DELETE'][$path] = $callback;
    }

    public function registerGuard($guardInstance)
    {
        $this->guards[] = $guardInstance;
    }

    private function getAllPatternFromRoutes()
    {
        return array_unique(
            array_keys(
                array_merge(
                    $this->routes['GET'] ?? [],
                    $this->routes['POST'] ?? [],
                    $this->routes['PUT'] ?? [],
                    $this->routes['PATCH'] ?? [],
                    $this->routes['DELETE'] ?? []
                )
            )
        );
    }

    private function matchUri()
    {
        $requestUri = $this->request->getPath();
        $baseRegex = "/\{([^\/]+)\}/";
        $patterns = $this->getAllPatternFromRoutes();

        foreach ($patterns as $pattern) {
            $replacedRouteTemplate = '#^' . preg_replace($baseRegex, '(?<$1>[^\/]+)', $pattern) . '$#';
            $pathMatch = preg_match($replacedRouteTemplate, $requestUri, $matches);
            if ($pathMatch) {
                $this->request->setUriPattern($pattern);
                return true;
            }
        }
        return false;
    }

    private function getRequestCallback(Request $request)
    {
        $method = $request->getMethod();
        $callback = $this->routes[$method][$request->getUriPattern()] ?? false;
        if ($callback) {
            if (is_array($callback)) {
                $callback[0] = $this->container->getInstance($callback[0]);
            }
            return $callback;
        }
        return false;
    }

    private function executeAllGuards(ExecutionContext $context)
    {
        if (count($this->guards) === 0) {
            return;
        }
        foreach ($this->guards as $key => $value) {
            $guard = $this->container->getInstance($value);
            if ($guard instanceof CanActivate) {
                if (!$guard->canActivate($context)) {
                    return $this->response->json(msg: 'Unauthorized', code: 401);
                }
            }
        }
    }

    public function resolve()
    {
        if ($this->matchUri()) {
            $this->request->setParams();
            $this->request->setQuery();
            $this->request->setBody();
            $this->request->setHeaders();
            try {
                $callback = $this->getRequestCallback($this->request);
                if (is_array($callback)) {
                    $ctx = new ExecutionContextImpl($this->request, $this->response, $callback[0]::class, $callback[1]);
                    $this->executeAllGuards($ctx);
                }
                return $this->response->json(call_user_func($callback, $this->request, $this->response));
            } catch (\Exception $e) {
                $code = $e->getCode() == 0 ? 500 : $e->getCode();
                $msg = empty($e->getMessage()) ? "Internal Server Error" : $e->getMessage();
                return $this->response->json(msg: $msg, code: $code);
            }
        }

        return $this->response->json(msg: 'not found', code: 404);
    }
}

class ExecutionContextImpl implements ExecutionContext
{
    private Request $request;
    private Response $response;
    private string $class;
    private string $handler;

    public function __construct($request, $response, $class, $handler)
    {
        $this->request = $request;
        $this->response = $response;
        $this->class = $class;
        $this->handler = $handler;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getHandler(): string
    {
        return $this->handler;
    }
}