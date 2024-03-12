<?php
namespace demo\core;

class Router
{
    private array $routes = [];
    private Request $request;
    private Response $response;
    private Container $container;

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

    public function resolve()
    {
        if ($this->matchUri()) {
            $this->request->getParams();
            $this->request->getQuery();
            $this->request->getBody();
            $this->request->getHeaders();
            try {
                $callback = $this->getRequestCallback($this->request);
                if ($callback) {
                    return call_user_func($callback, $this->request, $this->response);
                }
            } catch (\Exception $e) {
                return $this->response->json(msg: $e->getMessage(), code: $e->getCode());
            }
        }

        return $this->response->json(msg: 'not found', code: 404);
    }
}