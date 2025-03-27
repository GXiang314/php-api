<?php
namespace demo\core\http;

interface ExecutionContext extends HttpArgumentHost
{

    /**
     * Returns the request handler class name.
     * 
     * @return string
     */
    function getClass(): string;

    /**
     * Returns a reference to the handler (method).
     * 
     * @return string
     */
    function getHandler(): string;
}
