<?php

namespace Kamui\Resources;

use Kamui\API;
use Kamui\Resource;

class VHS extends Resource
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
        return $this->api->sendPut("user/vhs", array(), $content, true, array(
            'Content-Type'  => 'application/json',
        ));
    }
    
    public function get()
    {
        return $this->api->sendGet("user/vhs", array(), true);
    }
    
    public function delete()
    {
        return $this->api->sendDelete("user/vhs", array(), true);
    }
}
