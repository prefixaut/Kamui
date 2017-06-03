<?php

namespace Kamui\Resources;

use \Kamui\API;
use \Kamui\Resource;

class ChannelFeed extends Resource
{
    public function __construct(API $api)
    {
        $this->api = $api;
    }
    
    public function getPost($channel, $post = null, $args = array())
    {
        $channel_id = $this->api->getUserID($channel);
        if (!$channel_id)
            return false;
        
        if (is_null($post))
            return $this->api->sendGet("feed/{$channel_id}/posts", $args, true);
        
        $post_id =  $this->api->getPostID($post);
        if (!$post_id)
            return false;
        
        return $this->api->sendGet("feed/{$channel_id}/posts/{$post_id}", $args, true);
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
        
        $this->sendPostJson("feed/{$id}/posts", $content, $args, true);
    }
    
    public function deletePost($channel, $post)
    {
        $channel_id = $this->getUserID($channel);
        $post_id = $this->getPostID($post);
        if (!$channel_id || !$post_id)
            return false;
        
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
        
        return $this->api->sendPostJson("feed/{$channel_id}/posts/{$post_id}/comments", $content, array(), true);
    }
    
    public function deleteComment($channel, $post, $comment)
    {
        $channel_id = $this->api->getUserID($channel);
        $post_id = $this->api->getPostID($post);
        $comment_id = $this->api->getCommentID($comment);
        if (!$channel_id || !$post_id || !$comment_id)
            return false;
        
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
        
        return $this->api->sendDelete("feed/{$channel_id}/posts/{$post_id}/comments/{$comment_id}/reactions", $args, true);
    }
}
