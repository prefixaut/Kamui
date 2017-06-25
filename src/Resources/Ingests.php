<?php

namespace Kamui\Resources;

use Kamui\API;
use Kamui\Resource;

class Ingests extends Resource
{
    public function __construct(API $api)
    {
        parent::__construct($api);
    }
    
    public function get()
    {
        return $this->api->sendGet('ingests');
    }
}
