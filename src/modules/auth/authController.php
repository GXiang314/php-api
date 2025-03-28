<?php

namespace demo\modules\auth;

use demo\core\Request;
use demo\decorators\Body;
use demo\decorators\Req;
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
    public function signIn(#[Body] SignInInputRequest $body)
    {
        $account = $body->account;
        $password = $body->password;
        if (empty($account) || empty($password)) {
            throw new \Exception("Account and password cannot be empty", 400);
        }
        return $this->authService->signIn($account, $password);
    }

    #[Roles(["admin", "user"])]
    public function me(#[Req] Request $request)
    {
        return $request->user();
    }
}

class SignInInputRequest
{
    public string $account;
    public string $password;
}