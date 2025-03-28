<?php
namespace demo\modules\auth;

use Firebase\JWT\JWT;

class AuthService
{
    private function signAccessToken($userData)
    {
        return JWT::encode([
            ...$userData,
            "iat" => time(),
            "exp" => time() + JwtConstant::JWT_CONSTANT_EXPIRATION_TIME,
        ], JwtConstant::getSecret(), JwtConstant::JWT_CONSTANT_ALGORITHM);
    }

    public function signIn($account, $password)
    {
        // Simulate a sign-in process

        if (
            $account === 'admin' &&
            password_verify(
                password: "password",
                hash: password_hash(password: $password, algo: PASSWORD_BCRYPT, options: [
                    "cost" => 10,
                ])
            )
        ) {
            $userData = [
                'id' => 1,
                'name' => 'Admin',
                'roles' => ['admin', 'user'],
            ];
            return [
                'access_token' => $this->signAccessToken($userData),
            ];
        } else {
            throw new \Exception('Invalid username or password', 400);
        }
    }
}