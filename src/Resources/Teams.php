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

class Teams extends Kamui\BaseResource
{
    public function __construct(Kamui\API $api)
    {
        parent::__construct($api);
    }
    
    public function all($limit = 25, $offset = 0)
    {
        if (!is_numeric($limit) || !is_numeric($offset))
            return false;
        
        $args = array(
            'limit'     => intval($limit),
            'offset'    => intval($offset),
        );
        
        return $this->api->sendGet('teams', $args);
    }
    
    public function get($team)
    {
        return $this->api->sendGet("teams/{$team}");
    }
}
