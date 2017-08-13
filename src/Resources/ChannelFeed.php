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

/**
 * Resource for the Channel-Feed Endpoint.
 *
 * @see https://dev.twitch.tv/docs/v5/reference/channel-feed/
 */
class ChannelFeed extends BaseResource
{
    public function __construct(API $api)
    {
        $this->api = $api;
    }
    
    /**
     * Gets either all or a specified post of a channel.
     * When the post is left out (or set to null) it'll retrieve all posts of
     * the channel.
     * 
     * @param string|integer|object|array $channel The Channel-ID or Channel-Object which hosts the Post(s)
     * @param string|integer|object|array|null $post The Post-ID or Post-Object or null to get all
     * @param array $args Array of Arguments as defined in the Twitch Documentation (unfiltered, but will be url-escaped)
     * @param boolean $auth If it should use the (optional) authentification in the request
     * @return false|object Object returned from the Twitch-API or false on failure
     * 
     * @throws \Kamui\Exceptions\InvalidRequestException When Exceptions are enabled and the request was invalid
     * @throws \Kamui\Exceptions\PermissionException When Exceptions are enabled and the endpoint requires permissions that aren't met
     * @throws \Kamui\Exceptions\AuthentificationException When Exceptions are enabled and the endpoint requires authentification (user-permission)
     *
     * @see https://dev.twitch.tv/docs/v5/reference/channel-feed/#get-multiple-feed-posts
     * @see https://dev.twitch.tv/docs/v5/reference/channel-feed/#get-feed-post
     */
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
    
    /**
     * Creates a post on the given Channel.
     * Requires the <code>channel_feed_edit</code> Scope (Auth)
     * 
     * @param string|integer|object|array $channel The Channel-ID or Channel-Object to where the post belongs to
     * @param string $content Raw content that is being posted
     * @param boolean $share If the content should be shared via social-media (When the Channel has them connected). Defaults to false
     * @return false|object Object returned from the Twitch-API or false on failure
     *
     * @throws \Kamui\Exceptions\InvalidRequestException When Exceptions are enabled and the request was invalid
     * @throws \Kamui\Exceptions\PermissionException When Exceptions are enabled and the endpoint requires permissions that aren't met
     * @throws \Kamui\Exceptions\AuthentificationException When Exceptions are enabled and the endpoint requires authentification (user-permission)
     * 
     * @see https://dev.twitch.tv/docs/v5/reference/channel-feed/#create-feed-post
     */
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
    
    /**
     * Deletes a specific Post from a Channel
     * Requires the <code>channel_feed_edit</code> Scope (Auth)
     * 
     * @param string|integer|object|array $channel The Channel-ID or Channel-Object which hosts the Post
     * @param string|integer|object|array $post The Post-ID or Post-Object you want to delete
     * @return false|object Object returned from the Twitch-API or false on failure
     *
     * @throws \Kamui\Exceptions\InvalidRequestException When Exceptions are enabled and the request was invalid
     * @throws \Kamui\Exceptions\PermissionException When Exceptions are enabled and the endpoint requires permissions that aren't met
     * @throws \Kamui\Exceptions\AuthentificationException When Exceptions are enabled and the endpoint requires authentification (user-permission)
     * 
     * @see https://dev.twitch.tv/docs/v5/reference/channel-feed/#delete-feed-post
     */
    public function deletePost($channel, $post)
    {
        $channel_id = $this->getUserID($channel);
        $post_id = $this->getPostID($post);
        if (!$channel_id || !$post_id)
            return false;
        
        $this->api->scope = 'channel_feed_edit';
        return $this->sendDelete("feed/{$channel_id}/posts/{$post_id}", array(), true);
    }
    
    /**
     * React/Create a reaction to a Post
     * Requires the <code>channel_feed_edit</code> Scope (Auth)
     * 
     * @param string|integer|object|array $channel The Channel-ID or Channel-Object which hosts the Post
     * @param string|object|array $post The Post-ID or Post-Object which should be reacted to
     * @param string|integer $emote The Emote-ID or Emote-Name which acts as reaction
     * @return false|object Object returned from the Twitch-API or false on failure
     *
     * @throws \Kamui\Exceptions\InvalidRequestException When Exceptions are enabled and the request was invalid
     * @throws \Kamui\Exceptions\PermissionException When Exceptions are enabled and the endpoint requires permissions that aren't met
     * @throws \Kamui\Exceptions\AuthentificationException When Exceptions are enabled and the endpoint requires authentification (user-permission)
     * 
     * @see https://dev.twitch.tv/docs/v5/reference/channel-feed/#create-reaction-to-a-feed-post
     */
    public function createPostReaction($channel, $post, $emote)
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
    
    /**
     * Unreact/Delete a reaction from a Post
     * Requires the <code>channel_feed_edit</code> Scope (Auth)
     * 
     * @param string|integer|object|array $channel The Channel-ID or Channel-Object which hosts the Post
     * @param string|object|array $post The Post-ID or Post-Object which has the reaction on it
     * @param string|integer $emote The Emote-ID or Emote-Name that should be removed
     * @return false|object Object from the Twitch-API or false on failure
     *
     * @throws \Kamui\Exceptions\InvalidRequestException When Exceptions are enabled and the request was invalid
     * @throws \Kamui\Exceptions\PermissionException When Exceptions are enabled and the endpoint requires permissions that aren't met
     * @throws \Kamui\Exceptions\AuthentificationException When Exceptions are enabled and the endpoint requires authentification (user-permission)
     * 
     * @see https://dev.twitch.tv/docs/v5/reference/channel-feed/#delete-reaction-to-a-feed-post
     */
    public function deletePostReaction($channel, $post, $emote)
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
    
    /**
     * Gets all comments on a specified post in a specified channel feed.
     *
     * @param string|integer|object|array $channel The Channel-ID or Channel-Object which hosts the Post
     * @param string|object|array $post The Post-ID or Post-Object from which the comments will be loaded from
     * @param array $args Array of Arguments that will be passed to the request
     * @return false|object Object from the Twitch-API or false on failure
     * 
     * @throws \Kamui\Exceptions\InvalidRequestException When Exceptions are enabled and the request was invalid
     * @throws \Kamui\Exceptions\PermissionException When Exceptions are enabled and the endpoint requires permissions that aren't met
     * @throws \Kamui\Exceptions\AuthentificationException When Exceptions are enabled and the endpoint requires authentification (user-permission)
     * 
     * @see https://dev.twitch.tv/docs/v5/reference/channel-feed/#get-feed-comments
     */
    public function getComments($channel, $post, $args = array())
    {
        $channel_id = $this->api->getUserID($channel);
        $post_id = $this->api->getPostID($post);
        if (!$channel_id || !$post_id)
            return false;
        
        return $this->api->sendGet("feed/{$channel_id}/posts/{$post_id}/comments", $args, true);
    }
    
    /**
     * Creates a comment to a specified post in a specified channel feed.
     * Requires the <code>channel_feed_edit</code> Scope (Auth)
     *
     * @param string|integer|object|array $channel The Channel-ID or Channel-Object which hosts the Post
     * @param string|object|array $post The Post-ID or Post-Object which should be commented to
     * @param mixed $content The content of the Comment
     * @return false|object Object from the Twitch-API or false on failure
     * 
     * @throws \Kamui\Exceptions\InvalidRequestException When Exceptions are enabled and the request was invalid
     * @throws \Kamui\Exceptions\PermissionException When Exceptions are enabled and the endpoint requires permissions that aren't met
     * @throws \Kamui\Exceptions\AuthentificationException When Exceptions are enabled and the endpoint requires authentification (user-permission)
     * 
     * @see https://dev.twitch.tv/docs/v5/reference/channel-feed/#create-feed-comment
     */
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
    
    /**
     * Deletes a specified comment on a specified post in a specified channel feed.
     * Requires the <code>channel_feed_edit</code> Scope (Auth)
     *
     * @param string|integer|object|array $channel The Channel-ID or Channel-Object which hosts the Post
     * @param string|object|array $post The Post-ID or Post-Object which hosts the comment
     * @param string|integer|object|array $comment The Comment-ID or Comment-Object which should be deleted
     * @return false|object Object from the Twitch-API or false on failure
     * 
     * @throws \Kamui\Exceptions\InvalidRequestException When Exceptions are enabled and the request was invalid
     * @throws \Kamui\Exceptions\PermissionException When Exceptions are enabled and the endpoint requires permissions that aren't met
     * @throws \Kamui\Exceptions\AuthentificationException When Exceptions are enabled and the endpoint requires authentification (user-permission)
     * 
     * @see https://dev.twitch.tv/docs/v5/reference/channel-feed/#delete-feed-comment
     */
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
    
    /**
     * Creates a reaction to a specified post in a specified channel feed.
     * The reaction is specified by an emote value, which is either an ID (for example, “25” is Kappa),
     * the simple Emote-Name or the Emote-Object provided by the Emote-Helper.
     * Requires the <code>channel_feed_edit</code> Scope (Auth)
     *
     * @param string|integer|object|array $channel The Channel-ID or Channel-Object which hosts the Post
     * @param string|object|array $post The Post-ID or Post-Object which hosts the comment
     * @param string|integer|object|array $comment The Comment-ID or Comment-Object which should be reacted to
     * @param string|integer|object|array $emote The Emote-ID, Emote-Name or Emote-Object which should be used for the reaction
     * @return false|object Object from the Twitch-API or false on failure
     * 
     * @throws \Kamui\Exceptions\InvalidRequestException When Exceptions are enabled and the request was invalid
     * @throws \Kamui\Exceptions\PermissionException When Exceptions are enabled and the endpoint requires permissions that aren't met
     * @throws \Kamui\Exceptions\AuthentificationException When Exceptions are enabled and the endpoint requires authentification (user-permission)
     * 
     * @see https://dev.twitch.tv/docs/v5/reference/channel-feed/#create-reaction-to-a-feed-comment
     */
    public function createCommentReaction($channel, $post, $comment, $emote)
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
    
    /**
     * Deletes a specified reaction to a specified post in a specified channel feed.
     * The reaction is specified by an emote value, which is either an ID (for example, “25” is Kappa),
     * the simple Emote-Name or the Emote-Object provided by the Emote-Helper.
     * Requires the <code>channel_feed_edit</code> Scope (Auth)
     *
     * @param string|integer|object|array $channel The Channel-ID or Channel-Object which hosts the Post
     * @param string|object|array $post The Post-ID or Post-Object which hosts the comment
     * @param string|integer|object|array $comment The Comment-ID or Comment-Object which should be un-reacted to
     * @param string|integer|object|array $emote The Emote-ID, Emote-Name or Emote-Object which should be used for the deletion
     * @return false|object Object from the Twitch-API or false on failure
     * 
     * @throws \Kamui\Exceptions\InvalidRequestException When Exceptions are enabled and the request was invalid
     * @throws \Kamui\Exceptions\PermissionException When Exceptions are enabled and the endpoint requires permissions that aren't met
     * @throws \Kamui\Exceptions\AuthentificationException When Exceptions are enabled and the endpoint requires authentification (user-permission)
     * 
     * @see https://dev.twitch.tv/docs/v5/reference/channel-feed/#delete-reaction-to-a-feed-comment
     */
    public function deleteCommentReaction($channel, $post, $comment, $emote)
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
