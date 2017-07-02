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

namespace Kamui\Test\Resources;

use Kamui\Test\ResourceCase;

class ChannelsTest extends ResourceCase
{
    function testGetMe()
    {
        $data = $this->api->channels->get();
        
        $this->assertNotFalse($data);
        $this->assertEquals($this->id, $data->_id);
        $this->assertEquals(strtolower($this->name), strtolower($data->name));
    }
    
    function testGetByName()
    {
        $data = $this->api->channels->get($this->name);
        
        $this->assertNotFalse($data);
        $this->assertEquals($this->id, $data->_id);
        $this->assertEquals(strtolower($this->name), strtolower($data->name));
    }
    
    function testGetByID()
    {
        $data = $this->api->channels->get($this->id);
        
        $this->assertNotFalse($data);
        $this->assertEquals($this->id, $data->_id);
        $this->assertEquals(strtolower($this->name), strtolower($data->name));
    }
    
    function testUpdate()
    {
        $data = $this->api->channels->update('prefixaut', array(
            'channel_feed_enabled' => false,
        ));
        
        $this->assertNotFalse($data);
        $this->assertEquals($this->id, $data->_id);
        $this->assertEquals(strtolower($this->name), strtolower($data->name));
        
        // Reset the changes
        $this->assertNotFalse($this->api->channels->update('prefixaut', array(
            'channel_feed_enabled' => true,
        )));
        
        // Test that we don't have access to other channels
        $this->assertFalse($this->api->channels->update('ESL_CSGO', array(
            'status'    => 'this will fail anyways',
        )));
    }
    
    function testEditors()
    {
        $data = $this->api->channels->editors($this->name);
        $this->assertNotFalse($data);
        $this->assertTrue(isset($data->users));
        $this->assertInternalType('array', $data->users);
    }
    
    function testFollowers()
    {
        $this->assertNotFalse($this->api->channels->followers('prefixaut'));
    }
    
    function testTeams()
    {
        $this->assertNotFalse($this->api->channels->teams('prefixaut'));
    }
}
