<?php

namespace Kamui\Resources;

use Kamui\API;
use Kamui\Resource;

class Clips extends Resource
{
    public function __construct(API $api)
    {
        parent::__construct($api);
    }
    
    public function get($slug)
    {
        return $this->api->sendGet("clips/{$slug}");
    }
    
    public function top($args = array())
    {
        return $this->api->sendGet('clips/top', $args);
    }
    
    public function followed($args = array())
    {
        return $this->api->sendGet('clips/followed', $args, true);
    }
}
