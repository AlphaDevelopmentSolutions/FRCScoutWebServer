<?php

class NavBarArray extends \ArrayObject
{
    /**
     * @param string $key
     * @param NavBar $val
     */
    public function offsetSet($key, $val) {
        if ($val instanceof NavBar) {
            return parent::offsetSet($key, $val);
        }
        throw new \InvalidArgumentException('Value must be a NavBarLink');
    }
}