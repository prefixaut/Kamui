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

class ChannelFeed extends Resource
{
    public function __construct(API $api)
    {
        $this->api = $api;
    }
    
    public function getPost($channel, $post = null, $args = array(), $auth = false)
    {
        $channel_id = $this->api->getUserID($channel);
        if (!$channel_id)
            return false;
        
        if (is_null($post))
            return $this->api->sendGet("feed/{$channel_id}/posts", $args, $auth);
        
        $post_id =  $this->api->getPostID($post);
        if (!$post_id)
            return false;
        
        if ($auth)
            $this->api->scope = 'user_ids';
        
        return $this->api->sendGet("feed/{$channel_id}/posts/{$post_id}", $args, $auth);
    }
    
    public function createPost($channel, $content, $share = false)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        $args = array(
            'share' => $share,
        );
        
        $content = array(
            'content'   => $content,
        );
        
        $this->api->scope = 'channel_feed_edit';
        $this->sendPostJson("feed/{$id}/posts", $content, $args, true);
    }
    
    public function deletePost($channel, $post)
    {
        $channel_id = $this->getUserID($channel);
        $post_id = $this->getPostID($post);
        if (!$channel_id || !$post_id)
            return false;
        
        $this->api->scope = 'channel_feed_edit';
        return $this->sendDelete("feed/{$channel_id}/posts/{$post_id}", array(), true);
    }
    
    public function reactToPost($channel, $post, $emote)
    {
        $channel_id = $this->getUserID($channel);
        $post_id = $this->getPostID($post);
        $emote_id = $this->getEmoteID($emote);
        if (!$channel_id || !$post_id || !$emote_id)
            return false;
        
        $args = array(
            'emote_id'  => $emote_id,
        );
        
        $this->api->scope = 'channel_feed_edit';
        return $this->api->sendPost("feed/{$channel_id}/posts/{$post_id}/reactions", $args, null, true);
    }
    
    public function unreactToPost($channel, $post, $emote)
    {
        $channel_id = $this->getUserID($channel);
        $post_id = $this->getPostID($post);
        $emote_id = $this->getEmoteID($emote);
        if (!$channel_id || !$post_id || !$emote_id)
            return false;
        
        $args = array(
            'emote_id'  => $emote_id,
        );
        
        $this->api->scope = 'channel_feed_edit';
        return $this->api->sendDelete("feed/{$channel_id}/posts/{$post_id}/reactions", $args, true);
    
    }
    
    public function getComments($channel, $post, $args = array())
    {
        $channel_id = $this->api->getUserID($channel);
        $post_id = $this->api->getPostID($post);
        if (!$channel_id || !$post_id)
            return false;
        
        return $this->api->sendGet("feed/{$channel_id}/posts/{$post_id}/comments", $args, true);
    }
    
    public function createComment($channel, $post, $content)
    {
        $channel_id = $this->api->getUserID($channel);
        $post_id = $this->api->getPostID($post);
        if (!$channel_id || !$post_id)
            return false;
        
        $content = array(
            'content'   => $content,
        );
        
        $this->api->scope = 'channel_feed_edit';
        return $this->api->sendPostJson("feed/{$channel_id}/posts/{$post_id}/comments", $content, array(), true);
    }
    
    public function deleteComment($channel, $post, $comment)
    {
        $channel_id = $this->api->getUserID($channel);
        $post_id = $this->api->getPostID($post);
        $comment_id = $this->api->getCommentID($comment);
        if (!$channel_id || !$post_id || !$comment_id)
            return false;
        
        $this->api->scope = 'channel_feed_edit';
        return $this->api->sendDelete("feed/{$channel_id}/posts/{$post_id}/comments/{$comment_id}", array(), true);
    }
    
    public function reactToComment($channel, $post, $comment, $emote)
    {
        $channel_id = $this->api->getUserID($channel);
        $post_id = $this->api->getPostID($post);
        $comment_id = $this->api->getCommentID($comment);
        $emote_id = $this->api->getEmoteID($emote);
        if (!$channel_id || !$post_id || !$comment_id || !$emote_id)
            return false;
        
        $args = array(
            'emote_id'  => $emote_id,
        );
        
        $this->api->scope = 'channel_feed_edit';
        return $this->api->sendPost("feed/{$channel_id}/posts/{$post_id}/comments/{$comment_id}/reactions", $args, null, true);
    }
    
    public function dereactToComment($channel, $post, $comment, $emote)
    {
        $channel_id = $this->api->getUserID($channel);
        $post_id = $this->api->getPostID($post);
        $comment_id = $this->api->getCommentID($comment);
        $emote_id = $this->api->getEmoteID($emote);
        if (!$channel_id || !$post_id || !$comment_id || !$emote_id)
            return false;
        
        $args = array(
            'emote_id'  => $emote_id,
        );
        
        $this->api->scope = 'channel_feed_edit';
        return $this->api->sendDelete("feed/{$channel_id}/posts/{$post_id}/comments/{$comment_id}/reactions", $args, true);
    }
}
