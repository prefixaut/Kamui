<?php

namespace Kamui\Tests\Resources;

use Kamui\Test\ResourceCase;

class ChannelsTest extends ResourceCase
{
    function testGet()
    {
        $this->assertNotFalse($this->api->channels->get('prefixaut'));
    }
}
