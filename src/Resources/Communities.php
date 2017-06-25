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

class Communities extends Resource
{
    public function __construct(API $api)
    {
        parent::__construct($api);
    }
    
    public function get($community)
    {
        $id = $this->api->getCommunityID($community);
        
        if (!$id) {
            $args = array(
                'name'  => $community,
            );
            
            return $this->api->sendGet('communities', $args);
        }
        
        return $this->api->sendGet("communities/{$id}");
    }
    
    public function update($community, $args = array())
    {
        $id = $this->api->getCommunityID($community);
        if (!$id)
            return false;
        
        return $this->sendPutJson("communities/{$id}", $args, array(), true);
    }
    
    public function top($limit = 10, $cursor = null)
    {
        if (!is_numeric($limit))
            return false;
        
        $args = array(
            'limit' => intval($limit),
        );
        
        if (!is_null($cursor))
            $args['cursor'] = $cursor;
        
        return $this->api->sendGet('communities/top', $args);
    }
    
    public function bans($community, $limit = 10, $cursor = null)
    {
        $id = $this->api->getCommunityID($community);
        if (!$id || !is_numeric($limit))
            return false;
        
        $args = array(
            'limit' => intval($limit),
        );
        
        if (!is_null($cursor))
            $args['cursor'] = $cursor;
        
        return $this->api->sendGet("communities/{$id}/bans", $args, true);
    }
    
    public function ban($community, $user)
    {
        $community_id = $this->api->getCommunityID($community);
        $user_id = $this->api->getUserID($user);
        if (!$community_id || !$user_id)
            return false;
        
        return $this->api->sendPut("communities/{$community_id}/bans/{$user_id}", array(), null, true);
    }
    
    public function unban($community, $user)
    {
        $community_id = $this->api->getCommunityID($community);
        $user_id = $this->api->getUserID($user);
        if (!$community_id || !$user_id)
            return false;
        
        return $this->api->sendDelete("communities/{$community_id}/bans/{$user_id}", array(), true);
    }
    
    public function avatar($community, $image = false)
    {
        $id = $this->api->getCommunityID($community);
        if (!$id)
            return false;
        
        if (is_null($image)) {
            return $this->api->sendDelete("communities/{$community_id}/images/avatar", array(), true);
        }
        
        if (!is_string($image))
            return false;
        
        if (base64_decode($image, true) === false)
            $image = base64_encode($image);
        
        $data = array(
            'avatar_image'  => $image,
        );
        
        return $this->sendPostJson("communities/{$community_id}/images/avatar", $data, array(), true);
    }
    
    public function cover($community, $image = false)
    {
        $id = $this->api->getCommunityID($community);
        if (!$id)
            return false;
        
        if (is_null($image)) {
            return $this->api->sendDelete("communities/{$community_id}/images/cover", array(), true);
        }
        
        if (!is_string($image))
            return false;
        
        if (base64_decode($image, true) === false)
            $image = base64_encode($image);
        
        $data = array(
            'cover_image'  => $image,
        );
        
        return $this->sendPostJson("communities/{$community_id}/images/cover", $data, array(), true);
    }
    
    public function moderators($community)
    {
        $id = $this->api->getCommunityID($community);
        if (!$id)
            return false;
        
        return $this->api->sendGet("communities/{$id}/moderators", array(), true);
    }
    
    public function addModerator($community, $user)
    {
        $community_id = $this->api->getCommunityID($community);
        $user_id = $this->api->getUserID($user);
        if (!$community_id || !$user_id)
            return false;
        
        return $this->api->sendPut("communities/{$community_id}/moderators/{$user_id}", array(), null, true);
    }
    
    public function removeModerator($community, $user)
    {
        $community_id = $this->api->getCommunityID($community);
        $user_id = $this->api->getUserID($user);
        if (!$community_id || !$user_id)
            return false;
        
        return $this->api->sendDelete("communities/{$community_id}>/moderators/{$user_id}", array(), true);
    }
    
    public function permissions($community)
    {
        $id = $this->api->getCommunityID($community);
        if (!$id)
            return false;
        
        return $this->api->sendGet("communities/{$id}/permissions", array(), true);
    }
    
    public function report($community, $channel)
    {
        $community_id = $this->api->getCommunityID($community);
        $channel_id = $this->api->getUserID($channel);
        if (!$community_id || !$channel_id)
            return false;
        
        $data = array(
            'channel_id'    => $channel_id,
        );
        
        return $this->api->sendPostJson("communities/{$community_id}/report_channel", $data, array(), true);
    }
    
    public function timeouts($community, $limit = 10, $cursor = null)
    {
        $id = $this->api->getCommunityID($community);
        if (!$id || !is_numeric($limit))
            return false;
        
        $args = array(
            'limit' => intval($limit),
        );
        
        if (!is_null($cursor))
            $args['cursor'] = $cursor;
        
        return $this->api->sendGet("communities/{$id}/timeouts", $args, true);
    }
    
    public function timeout($community, $user, $duration, $reason = null)
    {
        $community_id = $this->api->getCommunityID($community);
        $user_id = $this->api->getUserID($user);
        if (!$community_id || !$user_id || !is_numeric($duration))
            return false;
        
        $data = array(
            'duration'  => intval($duration),
        );
        
        if (!is_null($reason))
            $data['reason'] = $reason;
        
        return $this->api->sendPutJson("communities/{$community_id}/timeouts/{$user_id}", $data, array(), true);
    }
    
    public function untimeout($community, $user)
    {
        $community_id = $this->api->getCommunityID($community);
        $user_id = $this->api->getUserID($user);
        if (!$community_id || !$user_id)
            return false;
        
        return $this->api->sendDelete("communities/{$community_id}/timeouts/{$user_id}", array(), true);
    }
}
