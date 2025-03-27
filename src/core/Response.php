<?php
namespace demo\core;

class Response
{
    public function setCookie($name, $value, $expire = 0, $sameSite = 'None', $secure = false, $httpOnly = true)
    {
        setcookie($name, $value, [
            'expires' => $expire,
            'path' => '/',
            'secure' => $secure,
            'httponly' => $httpOnly,
            'samesite' => $sameSite,
        ]);
    }

    public function json($data = null, $msg = "ok.", $code = 200)
    {
        header('Content-Type: application/json');
        http_response_code($code);
        $timeBetween = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
        echo json_encode([
            'code' => $code,
            'data' => $data,
            'message' => $msg,
            'execution_time' => round($timeBetween * 1000, 3) . "ms",
        ]);
        exit;
    }

    public function file($base64, $fileName, $code = 200)
    {
        $decoded = base64_decode($base64);
        file_put_contents($fileName, $decoded);

        if (file_exists($fileName)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileName));
            http_response_code($code);
            readfile($fileName);
        }
        exit;
    }
}