<?php

class NavBarLinkArray extends \ArrayObject
{
    /**
     * @param string $key
     * @param NavBarLink $val
     */
    public function offsetSet($key, $val) {
        if ($val instanceof NavBarLink) {
            return parent::offsetSet($key, $val);
        }
        throw new \InvalidArgumentException('Value must be a NavBarLink');
    }

}