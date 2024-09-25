<?php

namespace Jiannius\Atom\Services;

class Atom
{
    // call
    public function __call($name, $args = [])
    {
        return $this->createService($name, $args);
    }

    // create service
    public function createService($name, $args = [])
    {
        $name = str()->studly($name);
        $class = "\Jiannius\Atom\Services\\$name";
        $singleton = defined("$class::SINGLETON") ? $class::SINGLETON : false;

        if ($singleton) {
            if (!collect(app()->getBindings())->has($class)) app()->singleton($class);
            return app($class);
        }

        return new $class($args);
    }
}