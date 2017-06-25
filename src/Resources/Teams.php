<?php

namespace Kamui\Resources;

use Kamui\API;
use Kamui\Resource;

class Teams extends Resource
{
    public function __construct(API $api)
    {
        parent::__construct($api);
    }
    
    public function all($limit = 25, $offset = 0)
    {
        if (!is_numeric($limit) || !is_numeric($offset))
            return false;
        
        $args = array(
            'limit'     => intval($limit),
            'offset'    => intval($offset),
        );
        
        return $this->api->sendGet('teams', $args);
    }
    
    public function get($team)
    {
        return $this->api->sendGet("teams/{$team}");
    }
}
