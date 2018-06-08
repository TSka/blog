<?php

namespace Framework\Container;

class Container implements ContainerInterface
{
    private $definitions = [];
    private $results = [];
    private static $instance;

    public static function getInstance(): self
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}

    public function get($id)
    {
        if (array_key_exists($id, $this->results)) {
            return $this->results[$id];
        }

        if (!array_key_exists($id, $this->definitions)) {
            if (class_exists($id)) {
                $reflection = new \ReflectionClass($id);
                $arguments = [];
                if (($constructor = $reflection->getConstructor()) !== null) {
                    foreach ($constructor->getParameters() as $parameter) {
                        if ($paramClass = $parameter->getClass()) {
                            $arguments[] = $this->get($paramClass->getName());
                        } elseif ($parameter->isArray()) {
                            $arguments[] = [];
                        } else {
                            if (!$parameter->isDefaultValueAvailable()) {
                                throw new ServiceNotFoundException(sprintf('Unable to resolve "%s" in service %s', $parameter->getName(), $id));
                            }
                            $arguments[] = $parameter->getDefaultValue();
                        }
                    }
                }
                return $this->results[$id] = $reflection->newInstanceArgs($arguments);
            }
            throw new ServiceNotFoundException(sprintf('Unknown service "%s"', $id));
        }

        $definition = $this->definitions[$id];

        if ($definition instanceof \Closure) {
            $this->results[$id] = $definition($this);
        } else {
            $this->results[$id] = $definition;
        }

        return $this->results[$id];
    }

    public function has($id): bool
    {
        return array_key_exists($id, $this->definitions) || class_exists($id);
    }

    public function set($id, $value): void
    {
        if (array_key_exists($id, $this->results)) {
            unset($this->results[$id]);
        }
        $this->definitions[$id] = $value;
    }
}