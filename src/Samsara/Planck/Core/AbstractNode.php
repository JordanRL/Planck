<?php

namespace Samsara\Planck\Core;

abstract class AbstractNode
{

    protected $properties = [];

    public function set($key, $value)
    {
        $this->properties[$key] = $value;

        return $this;
    }

    public function get($key)
    {
        if (array_key_exists($key, $this->properties)) {
            return $this->properties[$key];
        }

        return null;
    }

    public function getAllProperties()
    {
        return $this->properties;
    }

}