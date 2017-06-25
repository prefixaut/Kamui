<?php

namespace Kamui\Util;

use Kamui\Util\APICache;

class MemoryCache implements APICache
{
    private $cache = array();
    /**
     * @var int Duration in seconds. -1 to store it forever
     */
    private $duration = -1;
    
    public function __construct() {}
    
    public function set($key, $value, $duration = null)
    {
        if (is_null($duration)) {
            $duration = $this->getDefaultDuration();
        }
        $interval = new \DateInterval(($duration < 0) ? 'P2Y' : 'PT' . $duration . 'S');
        
        $this->cache[$key] = array(
            'valid' => (new \DateTime())->add($interval),
            'data'  => $value,
        );
        
        return true;
    }
    
    public function get($key)
    {
        if (!isset($this->cache[$key]))
            return false;
        
        $data = $this->cache[$key];
        
        if ($data['valid'] <= new \DateTime()) {
            unset($this->cache[$key]);
            return false;
        }
        
        return $data['data'];
    }
    
    public function clear()
    {
        $this->cache = array();
    }
    
    public function all()
    {
        $arr = array();
        foreach (array_keys($this->cache) as $key => $val) {
            $dat = $this->get($key);
            if ($dat)
                $arr[$key] = $dat;
        }
        return $arr;
    }
    
    public function getDefaultDuration()
    {
        return $this->duration;
    }
    
    public function setDefaultDuration($duration)
    {
        $this->duration = $duration;
    }
}
