<?php
/**
 * Copyright (c) 2017 PreFiXAUT
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

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
