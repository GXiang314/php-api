<?php
namespace demo\public;

use demo\core\Application;
use demo\controllers\demoController;

require "../../vendor/autoload.php";

$app = new Application();

$app->router->get('/api/demo', [demoController::class, 'index']);

$app->run();

