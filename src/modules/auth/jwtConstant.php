<?php

namespace demo\modules\auth;

class JwtConstant
{
    private static $jwtSecret = null;
    public static function getSecret() {
        if (self::$jwtSecret === null) {
            self::$jwtSecret = $_ENV["AUTH_SECRET"] ?? 'default_secret';
        }
        return self::$jwtSecret;
    } 
    public const JWT_CONSTANT_ALGORITHM = "HS256";
    public const JWT_CONSTANT_EXPIRATION_TIME = 3600; // 1 hour

}
