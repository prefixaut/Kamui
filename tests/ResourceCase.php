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

namespace Kamui\Test;

use PHPUnit\Framework\TestCase;
use Kamui\API;

class ResourceCase extends TestCase
{
    protected $api;
    protected $name = 'prefixaut';
    protected $id = 25391134;
    private $root;
    
    public function setUp()
    {
        $root = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
        
        $client_id = $this->getVaribale('TWITCH_CLIENT_ID', 'client_id.txt');
        $secret = $this->getVaribale('TWITCH_CLIENT_SECRET', 'secret.txt');
        $oauth = $this->getVaribale('TWITCH_OAUTH_TOKEN', 'oauth.txt');
        
        $this->api = new API($client_id, $secret, $oauth);
    }
    
    private function getVaribale($env, $file)
    {
        if (isset($_ENV[$env]))
            return $_ENV[$env];
        
        if (is_readable($this->root . $file))
            return trim(@file_get_contents($this->root . $file));
        
        return null;
    }
}
