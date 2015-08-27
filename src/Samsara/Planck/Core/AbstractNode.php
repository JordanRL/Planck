<?php

namespace Samsara\Planck\Core;

abstract class AbstractNode
{
    /**
     * @var array
     */
    protected $properties = [];

    /**
     * @param   string  $key
     * @param   string  $value
     * @return  $this
     */
    public function set($key, $value)
    {
        $this->properties[$key] = $value;

        return $this;
    }

    /**
     * @param   string  $key
     * @return  null|mixed
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->properties)) {
            return $this->properties[$key];
        }

        return null;
    }

    /**
     * @return  array
     */
    public function getAllProperties()
    {
        return $this->properties;
    }

}