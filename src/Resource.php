<?php

namespace Kamui;

use Kamui\API;

class Resource
{
    protected $api;
    
    public function __construct(API $api)
    {
        $this->api = $api;
    }
}
