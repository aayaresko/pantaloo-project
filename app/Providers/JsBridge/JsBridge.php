<?php

namespace App\Providers\JsBridge;

class JsBridge implements \ArrayAccess
{
    private $container;

    public function __construct()
    {
        $this->container = [];
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    public function __toString()
    {
        return json_encode($this->container);
    }
}
