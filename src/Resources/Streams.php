<?php

namespace Kamui\Resources;

use \Kamui\API;
use \Kamui\Resource;

class Streams extends Resource
{
    public function __construct(API $api)
    {
        parent::__construct($api);
    }
    
    public function get($channel, $type = 'live')
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        $args = array(
            'stream_type'   => $type,
        );
        
        return $this->api->sendGet("streams/{$id}", $args);
    }
    
    public function all($args = array())
    {
        return $this->api->sendGet('streams', $args);
    }
    
    public function summary($game = null)
    {
        $args = array();
        if (!is_null($game))
            $args['game'] = $game;
        
        return $this->api->sendGet('streams/summary', $args);
    }
    
    public function featured($limit = 25, $offset = 0)
    {
        $args = array(
            'limit'     => $limit,
            'offset'    => $offset,
        );
        
        return $this->api->sendGet('streams/featured', $args);
    }
    
    public function followed($type = 'live', $limit = 25, $offset = 0)
    {
        $args = array(
            'type'      => $type,
            'limit'     => $limit,
            'offset'    => $offset,
        );
        
        return $this->api->sendGet('streams/followed', $args, true);
    }
}
