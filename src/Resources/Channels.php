<?php

namespace Kamui\Resources;

use Kamui\API;
use Kamui\Resource;

class Channels extends Resource
{
    public function __construct(API $api)
    {
        parent::__construct($api);
    }
    
    public function get($channel = null)
    {
        if (is_null($channel))
            return $this->api->sendGet('channel', array(), true);
        
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        return $this->api->sendGet("channels/{$id}");
    }
    
    public function update($channel, $args)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        return $this->api->sendPutJson("channels/{$id}", $args, array(), true);
    }
    
    public function editors($channel)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        return $this->api->sendGet("channels/{$id}/editors", array(), true);
    }
    
    public function followers($channel)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        return $this->api->sendGet("channels/{$id}/follows");
    }
    
    public function teams($channel)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        return $this->api->sendGet("channels/{$id}/teams");
    }
    
    public function subscribers($channel, $args = array())
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        return $this->api->sendGet("channels/{$id}/subscriptions", $args, true);
    }
    
    public function subscriber($channel, $user)
    {
        $channel_id = $this->api->getUserID($channel);
        $user_id = $this->api->getUserID($user);
        if (!$channel_id || !$user_id)
            return false;
        
        return $this->api->sendGet("channels/{$channel_id}/subscriptions/{$user_id}", array(), true);
    }
    
    public function videos($channel)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        return $this->api->sendGet("channels/{$id}/videos");
    }
    
    public function startCommercial($channel, $duration)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        $content = array(
            'duration'  => $duration,
        );
        
        return $this->api->sendPostJson("channels/{$id}/commercial", $content, array(), true);
    }
    
    public function resetStreamKey($channel)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        return $this->api->sendDelete("channels/{$id}/stream_key", array(), true);
    }
    
    public function getCommunity($channel)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        return $this->api->sendGet("channels/{$id}/community");
    }
    
    public function setCommunity($channel, $community)
    {
        $channel_id = $this->api->getUserID($channel);
        $community_id = $this->api->getCommunityID($community);
        if (!$channel_id || !$community_id)
            return false;
        
        return $this->api->sendPut("channels/{$channel_id}/community/{$community_id}", array(), null, true);
    }
    
    public function deleteCommunity($channel)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        return $this->api->sendDelete("channels/{$id}/community", array(), true);
    }
}
