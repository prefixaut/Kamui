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
use Kamui\BaseResource;

class VHS extends BaseResource
{
    public function __construct(API $api)
    {
        parent::__construct($api);
    }
    
    public function create($identifier)
    {
        $content = json_encode(array(
            'identifier'    => $identifier,
        ));
        
        $this->api->scope = 'viewing_activity_read';
        return $this->api->sendPut("user/vhs", array(), $content, true, array(
            'Content-Type'  => 'application/json',
        ));
    }
    
    public function get()
    {
        $this->api->scope = 'user_read';
        return $this->api->sendGet("user/vhs", array(), true);
    }
    
    public function delete()
    {
        $this->api->scope = 'viewing_activity_read';
        return $this->api->sendDelete("user/vhs", array(), true);
    }
}
