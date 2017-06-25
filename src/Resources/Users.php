<?php

namespace Kamui\Resources;

use Kamui\API;
use Kamui\Resource;

class Users extends Resource
{
    public function __construct(API $api)
    {
        parent::__construct($api);
    }
    
    public function get($user = null)
    {
        if (is_null($user)) {
            return $this->api->sendGet('user', array(), $true);
        }
        
        $id = $this->api->getUserID($user, false);
        if (!$id) {
            $args = array(
                'login' => $user,
            );
            
            return $this->api->sendGet('users', $args);
        }
        
        return $this->api->sendGet("users/{$id}");
    }
    
    public function emotes($user)
    {
        $id = $this->api->getUserID($user);
        if (!$id)
            return false;
        
        return $this->api->sendGet("users/{$id}/emotes");
    }
    
    public function subscriptions($user, $channel)
    {
        $user_id = $this->api->getUserID($user);
        $channel_id = $this->api->getUserID($channel);
        if (!$user_id || !$channel_id)
            return false;
        
        return $this->api->sendGet("users/{$id}/subscriptions/{$channel_id}", array(), true);
    }
    
    public function follows($user, $channel = null)
    {
        $user_id = $this->api->getUserID($user);
        if (!$user_id)
            return false;
            
        if (!is_null($channel)) {
            $channel_id = $this->api->getUserID($channel);
            if (!$channel_id)
                return false;
            
            return $this->api->sendGet("users/{$user_id}/follows/channels/{$channel_id}");
        }
        
        return $this->api->sendGet("users/{$user_id}/follows/channels");
    }
    
    public function follow($user, $channel)
    {
        $user_id = $this->api->getUserID($user);
        $channel_id = $this->api->getUserID($channel);
        if (!$user_id || $channel_id)
            return false;
        
        return $this->api->sendPut("users/{$user_id}/follows/channels/{$channel_id}", array(), null, true);
    }
    
    public function unfolllow($user, $channel)
    {
        $user_id = $this->api->getUserID($user);
        $channel_id = $this->api->getUserID($channel);
        if (!$user_id || !$channel_id)
            return false;
        
        return $this->api->sendDelete("users/{$user_id}/follows/channels/{$channel_id}", array(), null, true);
    }
    
    public function blocks($user, $limit = 25, $offset = 0)
    {
        $id = $this->api->getUserID($user);
        if (!$id)
            return false;
        
        $args = array(
            'limit'     => $limit,
            'offset'    => $offset,
        );
        
        return $this->api->sendGet("users/{$id}/blocks", $args, true);
    }
    
    public function block($source, $target)
    {
        $source_id = $this->api->getUserID($source);
        $target_id = $this->api->getUserID($target);
        if (!$source_id || !$target_id)
            return false;
        
        return $this->api->sendPut("users/{$source_id}/blocks/{$target_id}", array(), null, true);
    }
    
    public function unblock($source, $target)
    {
        $source_id = $this->api->getUserID($source);
        $target_id = $this->api->getUserID($target);
        if (!$source_id || !$target_id)
            return false;
        
        return $this->api->sendDelete("users/{$source_id}/blocks/{$target_id}", array(), true);
    }
}
