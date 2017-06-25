<?php

namespace Kamui\Util;

interface APICache
{
    public function get($key);
    
    public function set($key, $value, $duration = null);
    
    public function clear();
    
    public function all();
    
    public function getDefaultDuration();
    
    public function setDefaultDuration($duration);
}
