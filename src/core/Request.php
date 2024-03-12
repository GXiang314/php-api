<?php
namespace demo\core;

class Request
{
    public array $query = [];
    public array $body = [];
    public array $params = [];
    private string $uriPattern = '';
    public array $headers = [];

    public function setUriPattern(string $uriPattern): void
    {
        $this->uriPattern = $uriPattern;
    }

    public function getUriPattern(): string
    {
        return $this->uriPattern;
    }

    public function getParams()
    {
        $uri = explode('?', $this->getPath())[0];
        $baseRegex = "/\{([^\/]+)\}/";
        $replacedRouteTemplate = '#^' . preg_replace($baseRegex, '(?<$1>[^\/]+)', $this->uriPattern) . '$#';
        preg_match($replacedRouteTemplate, $uri, $matches);
        preg_match_all('/\{([^\/]+)\}/', $this->uriPattern, $paramNames);
        if (count($paramNames[0]) > 0) {
            foreach ($paramNames[1] as $name) {
                $this->params[$name] = $matches[$name];
            }
        }
        return $this->params;
    }

    public function getQuery()
    {
        $this->query = $_GET;
        return $this->query;
    }

    public function getBody()
    {
        $this->body = $_POST;
        $body = json_decode(file_get_contents('php://input')) ?? [];
        foreach ($body as $key => $value) {
            $this->body[$key] = $value;
        }
        return $this->body;
    }

    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        return $path;
    }

    public function getMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        return $method;
    }

    public function getHeaders()
    {
        $this->headers = getallheaders();
        return $this->headers;
    }

    public function getCookies()
    {
        return $_COOKIE;
    }
}