<?php
namespace demo\core\http;

use demo\core\Request;
use demo\core\Response;

interface HttpArgumentHost
{
   public function getRequest(): Request;
   public function getResponse(): Response;
}
