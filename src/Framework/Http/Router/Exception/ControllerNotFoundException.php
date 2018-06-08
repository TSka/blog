<?php

namespace Framework\Http\Router\Exception;

class ControllerNotFoundException extends \InvalidArgumentException
{
    private $controller;

    public function __construct($controller)
    {
        parent::__construct(sprintf('Controller %s not found', $controller));
        $this->controller = $controller;
    }

    public function getController()
    {
        return $this->controller;
    }
}