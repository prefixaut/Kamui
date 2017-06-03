<?php

namespace Kamui\Resources;

use \Kamui\API;
use \Kamui\Resource;

class Bits extends Resource
{
    public function __construct(API $api)
    {
        parent::__construct($api);
    }
    
    public function get($channel = null)
    {
        $args = array();
        if (!is_null($channel)) {
            $id = $this->api->getUserID($channel);
            if (!$id)
                return false;
            $args['channel_id'] = $id;
        }
        
        return $this->api->sendGet('bits/actions', $args);
    }
}
