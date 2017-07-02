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
use Kamui\Resource;

class Channels extends Resource
{
    public function __construct(API $api)
    {
        parent::__construct($api);
    }
    
    public function get($channel = null)
    {
        if (is_null($channel)) {
            $this->api->scope = 'channel_read';
            return $this->api->sendGet('channel', array(), true);
        }
        
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        return $this->api->sendGet("channels/{$id}");
    }
    
    public function update($channel, $content)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
            
        $data = array(
            'channel'   => $content,
        );
        
        $this->api->scope = 'channel_editor';
        return $this->api->sendPutJson("channels/{$id}", $data, array(), true);
    }
    
    public function editors($channel)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        $this->api->scope = 'channel_read';
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
        
        $this->api->scope = 'channel_subscriptions';
        return $this->api->sendGet("channels/{$id}/subscriptions", $args, true);
    }
    
    public function subscriber($channel, $user)
    {
        $channel_id = $this->api->getUserID($channel);
        $user_id = $this->api->getUserID($user);
        if (!$channel_id || !$user_id)
            return false;
        
        $this->api->scope = 'channel_check_subscription';
        return $this->api->sendGet("channels/{$channel_id}/subscriptions/{$user_id}", array(), true);
    }
    
    public function videos($channel)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        return $this->api->sendGet("channels/{$id}/videos");
    }
    
    public function collections($channel, $args = array())
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        return $this->api->sendGet("channels/{$id}/collections", $args);
    }
    
    public function startCommercial($channel, $duration)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        $content = array(
            'duration'  => $duration,
        );
        
        $this->api->scope = 'channel_commercial';
        return $this->api->sendPostJson("channels/{$id}/commercial", $content, array(), true);
    }
    
    public function resetStreamKey($channel)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        $this->api->scope = 'channel_stream';
        return $this->api->sendDelete("channels/{$id}/stream_key", array(), true);
    }
    
    public function getCommunity($channel)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        $this->api->scope = 'channel_editor';
        return $this->api->sendGet("channels/{$id}/community");
    }
    
    public function setCommunity($channel, $community)
    {
        $channel_id = $this->api->getUserID($channel);
        $community_id = $this->api->getCommunityID($community);
        if (!$channel_id || !$community_id)
            return false;
        
        $this->api->scope = 'channel_editor';
        return $this->api->sendPut("channels/{$channel_id}/community/{$community_id}", array(), null, true);
    }
    
    public function deleteCommunity($channel)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        $this->api->scope = 'channel_editor';
        return $this->api->sendDelete("channels/{$id}/community", array(), true);
    }
}
