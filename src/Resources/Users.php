<?php
/**
 * Copyright (c) 2017 PreFiXAUT
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Kamui\Resources;

use Kamui\API;
use Kamui\BaseResource;

class Users extends BaseResource
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
        
        $this->api->scope = 'user_subscriptions';
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
        
        $this->api->scope = 'user_follows_edit';
        return $this->api->sendPut("users/{$user_id}/follows/channels/{$channel_id}", array(), null, true);
    }
    
    public function unfolllow($user, $channel)
    {
        $user_id = $this->api->getUserID($user);
        $channel_id = $this->api->getUserID($channel);
        if (!$user_id || !$channel_id)
            return false;
        
        $this->api->scope = 'user_follows_edit';
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
        
        $this->api->scope = 'user_blocks_read';
        return $this->api->sendGet("users/{$id}/blocks", $args, true);
    }
    
    public function block($source, $target)
    {
        $source_id = $this->api->getUserID($source);
        $target_id = $this->api->getUserID($target);
        if (!$source_id || !$target_id)
            return false;
        
        $this->api->scope = 'user_blocks_edit';
        return $this->api->sendPut("users/{$source_id}/blocks/{$target_id}", array(), null, true);
    }
    
    public function unblock($source, $target)
    {
        $source_id = $this->api->getUserID($source);
        $target_id = $this->api->getUserID($target);
        if (!$source_id || !$target_id)
            return false;
        
        $this->api->scope = 'user_blocks_edit';
        return $this->api->sendDelete("users/{$source_id}/blocks/{$target_id}", array(), true);
    }
}
