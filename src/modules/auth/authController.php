<?php

namespace demo\modules\auth;

use demo\core\Request;
use demo\core\Response;
use demo\decorators\Roles;
use demo\decorators\SkipAuth;

class AuthController
{
    private AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    #[SkipAuth()]
    public function signIn(Request $request, Response $response)
    {
        $account = $request->body["account"];
        $password = $request->body["password"];
        if (empty($account) || empty($password)) {
            throw new \Exception("Account and password cannot be empty", 400);
        }
        return $this->authService->signIn($account, $password);
    }

    #[Roles(["admin", "user"])]
    public function me(Request $request, Response $response)
    {
        return $request->user();
    }
}