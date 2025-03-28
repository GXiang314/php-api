<?php

namespace demo\guards;

use demo\core\Container;
use demo\core\guard\CanActivate;
use demo\core\http\ExecutionContext;
use demo\core\Request;
use demo\decorators\Singleton;
use demo\decorators\SkipAuth;
use demo\modules\auth\JwtConstant;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;

#[Singleton()]
class AuthGuard implements CanActivate
{
    public function canActivate(ExecutionContext $ctx): bool
    {
        if ($this->checkIsSkipAuth($ctx)) {
            return true;
        }

        $request = $ctx->getRequest();
        $token = $this->extractTokenFromHeader($request);
        if ($token === null) {
            return false;
        }

        $payload = $this->extractPayloadFromToken($token);
        if ($payload === null) {
            throw new \Exception('Invalid token');
        }

        if ($this->checkIsTokenExpired($payload)) {
            return false;
        }
        $request->setUserData($payload);

        return true;
    }

    private function checkIsSkipAuth(ExecutionContext $ctx): bool
    {
        $reflector = Container::getReflection($ctx->getClass());
        $method = $reflector->getMethod($ctx->getHandler());
        $classAttributes = $reflector->getAttributes(SkipAuth::class);
        $methodAttributes = $method->getAttributes(SkipAuth::class);
        if (count($methodAttributes) > 0) {
            $skipAuth = $methodAttributes[0]->newInstance();
            return $skipAuth->getIsPublic();
        }
        if (count($classAttributes) > 0) {
            $skipAuth = $classAttributes[0]->newInstance();
            return $skipAuth->getIsPublic();
        }
        return false;
    }

    private function checkIsTokenExpired(stdClass $payload): bool
    {
        $now = time();
        return $payload->exp < $now;
    }

    private function extractPayloadFromToken($token)
    {
        return JWT::decode($token, new Key(JwtConstant::getSecret(), JwtConstant::JWT_CONSTANT_ALGORITHM));
    }

    private function extractTokenFromHeader(Request $request): string|null
    {
        $authorization = $request->headers['Authorization'] ?? '';
        $parts = explode(' ', $authorization);
        $type = $parts[0] ?? '';
        $token = $parts[1] ?? '';
        return ($type === 'Bearer') ? $token : null;
    }

}