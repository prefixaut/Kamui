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

namespace Kamui\Resources;

use Kamui\API;
use Kamui\Resource;

class Streams extends Resource
{
    public function __construct(API $api)
    {
        parent::__construct($api);
    }
    
    public function get($channel, $type = 'live')
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        $args = array(
            'stream_type'   => $type,
        );
        
        return $this->api->sendGet("streams/{$id}", $args);
    }
    
    public function all($args = array())
    {
        return $this->api->sendGet('streams', $args);
    }
    
    public function summary($game = null)
    {
        $args = array();
        if (!is_null($game))
            $args['game'] = $game;
        
        return $this->api->sendGet('streams/summary', $args);
    }
    
    public function featured($limit = 25, $offset = 0)
    {
        $args = array(
            'limit'     => $limit,
            'offset'    => $offset,
        );
        
        return $this->api->sendGet('streams/featured', $args);
    }
    
    public function followed($type = 'live', $limit = 25, $offset = 0)
    {
        $args = array(
            'type'      => $type,
            'limit'     => $limit,
            'offset'    => $offset,
        );
        
        return $this->api->sendGet('streams/followed', $args, true);
    }
}
