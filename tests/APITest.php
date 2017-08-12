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

use Kamui\Test\ResourceCase;
use Kamui\API;

class APITest extends ResourceCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(API::class, $this->public);
        $this->assertInstanceOf(API::class, $this->personal);        
    }

    public function testProperties()
    {
        $id = $this->getVaribale('TWITCH_CLIENT_ID', 'client_id.txt');
        $secret = $this->getVaribale('TWITCH_CLIENT_SECRET', 'secret.txt');
        $auth = $this->getVaribale('TWITCH_AUTH_TOKEN', 'oauth.txt');

        $this->assertEquals($this->public->getClientID(), $id);
        $this->assertEquals($this->public->getClientSecret(), $secret);
        $this->assertEquals($this->public->getOAuthToken(), null);
        $this->assertFalse($this->public->isSilent());

        $this->assertEquals($this->personal->getClientID(), $id);
        $this->assertEquals($this->personal->getClientSecret(), $secret);
        $this->assertEquals($this->personal->getOAuthToken(), $auth);
        $this->assertFalse($this->personal->isSilent());
    }
}
