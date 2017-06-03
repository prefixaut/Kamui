<?php

namespace Kamui\Test;

use PHPUnit\Framework\TestCase;
use Kamui\API;

class ResourceCase
{
    
    public function setUp()
    {
        $key = (isset($_ENV['TWITCH_TOKEN'])) ? $_ENV['TWITCH_TOKEN'] : @\file_get_contents('../key.txt');
        $api = new API($key);
    }
}
