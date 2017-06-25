<?php

namespace Kamui\Test\Resources;

use Kamui\Test\ResourceCase;

class ChannelsTest extends ResourceCase
{
    function testGetByName()
    {
        $this->assertNotFalse($this->api->channels->get('prefixaut'));
    }
    
    function testGetByID()
    {
        $this->assertNotFalse($this->api->channels->get('25391134'));
    }
    
    function testEditors()
    {
        //$this->assertNotFalse($this->api->channels->editors('prefixaut'));
    }
}
