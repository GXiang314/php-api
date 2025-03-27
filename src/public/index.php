<?php
namespace demo\public;

use demo\core\Application;
use demo\modules\demo\demoController;

require "../../vendor/autoload.php";

$app = new Application();

$app->router->get('/api/demo', [demoController::class, 'index']);
$app->router->post('/api/demo', [demoController::class, 'create']);

$app->run();

