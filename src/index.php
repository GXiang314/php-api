<?php
namespace demo;

use demo\core\Application;
use demo\decorators\Param;
use demo\guards\AuthGuard;
use demo\modules\auth\AuthController;
use demo\modules\demo\DemoController;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->safeLoad();
$dotenv->required(['AUTH_SECRET']);

$app = new Application();

$app->cors();
$app->use(AuthGuard::class);

# class method
$app->router->get('/api/demo', [DemoController::class, 'index']);
$app->router->post('/api/demo', [DemoController::class, 'create']);
$app->router->post('/api/signin', [AuthController::class, 'signIn']);
$app->router->get('/api/me', [AuthController::class, 'me']);

# callback function
$app->router->get('/path/{id}', fn(#[Param('id')] string $id) => ['id' => $id]);
$app->run();

