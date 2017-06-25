<?php

namespace Kamui\Resources;

use Kamui\API;
use Kamui\Resource;

class Search extends Resource
{
    public function __construct(API $api)
    {
        parent::__construct($api);
    }
    
    public function channels($query, $limit = 25, $offset = 0)
    {
        if (!is_numeric($limit) || !is_numeric($offset))
            return false;
        
        $args = array(
            'query'     => $query,
            'limit'     => intval($limit),
            'offset'    => intval($offset),
        );
        
        return $this->api->sendGet('search/channels', $args);
    }
    
    public function games($query, $live = false)
    {
        $args = array(
            'query' => $query,
            'live'  => (bool) $live,
        );
        
        return $this->api->sendGet('search/games', $args);
    }
    
    public function streams($query, $limit = 25, $offset = 0, $hls = null)
    {
        if (!is_numeric($limit) || !is_numeric($offset))
            return false;
        
        $args = array(
            'query'     => $query,
            'limit'     => intval($limit),
            'offset'    => intval($offset),
        );
        
        if (!is_null($hls))
            $args['hls'] = (bool) $hls;
        
        return $this->api->sendGet('search/streams', $args);
    }
}
