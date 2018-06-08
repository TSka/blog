<?php

namespace Framework\Http\Router\Exception;

class UnknownRouteHandlerException extends \InvalidArgumentException
{
    private $handler;

    public function __construct($errors)
    {
        parent::__construct('Unknown route handler');
        $this->handler = $errors;
    }

    public function getHandler()
    {
        return $this->handler;
    }
}