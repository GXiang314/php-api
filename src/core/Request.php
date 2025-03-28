<?php
namespace demo\core;

class Request
{
    private mixed $user = null;
    private string $uriPattern = '';
    public array $query = [];
    public array $body = [];
    public array $params = [];
    public array $headers = [];

    public function setUserData(mixed $user): void
    {
        $this->user = $user;
    }

    public function user(): mixed
    {
        return $this->user;
    }

    public function setUriPattern(string $uriPattern): void
    {
        $this->uriPattern = $uriPattern;
    }

    public function getUriPattern(): string
    {
        return $this->uriPattern;
    }

    public function setParams()
    {
        $requestPath = $this->getPath();
        $replacedRouteTemplate = '#^' . preg_replace(
            Router::VAR_REGEX,
            Router::VAR_REGEX_REPLACEMENT,
            $this->uriPattern
        ) . '$#';
        preg_match($replacedRouteTemplate, $requestPath, $matches);
        preg_match_all(Router::VAR_REGEX, $this->uriPattern, $paramNames);
        if (count($paramNames[0]) > 0) {
            foreach ($paramNames[1] as $key) {
                $this->params[$key] = $matches[$key];
            }
        }
        return $this->params;
    }

    public function setQuery()
    {
        $this->query = $_GET;
    }

    public function setBody()
    {
        $this->body = $_POST;
        $body = json_decode(file_get_contents('php://input')) ?? [];
        foreach ($body as $key => $value) {
            $this->body[$key] = $value;
        }
    }

    public function getPath()
    {
        return explode('?', $_SERVER['REQUEST_URI'] ?? '/')[0];
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function setHeaders()
    {
        $this->headers = getallheaders();
    }

    public function getCookies()
    {
        return $_COOKIE;
    }
}