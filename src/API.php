<?php

namespace Kamui;

use Kamui\Helpers\Emotes;
use Kamui\Resources\Bits;
use Kamui\Resources\ChannelFeed;
use Kamui\Resources\Channels;
use Kamui\Resources\Streams;
use Kamui\Resources\Users;
use Kamui\Resources\VHS;

class API
{
    private $client_id;
    private $oauth_token;
    private $resources = array();
    private $base_url = "https://api.twitch.tv/kraken/";
    private $community_id_pattern = '^[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$';
    
    /* =========================================================================
     * ~~ Constructor
     * =======================================================================*/
    
    public function __construct($client_id, $oauth_token = null)
    {
        $this->client_id = $client_id;
        $this->oauth_token = $oauth_token;
        $this->setupResources();
    }
    
    /* =========================================================================
     * ~~ Magic Functions
     * =======================================================================*/
    
    public function __get($name)
    {
        if (isset($this->resources[$name]))
            return $this->resources[$name];
    }
    
    /* =========================================================================
     * ~~ Setup Functions
     * =======================================================================*/
    
    private function setupResources()
    {
        $this->resources['bits'] = new Bits($this);
        $this->resources['feed'] = new ChannelFeed($this);
        $this->resources['channels'] = new Channels($this);
        $this->resources['streams'] = new Streams($this);
        $this->resources['users'] = new Users($this);
        $this->resources['vhs'] = new VHS($this);
    }
    
    /* =========================================================================
     * ~~ Request Functions
     * =======================================================================*/
    
    public function sendGet($endpoint, $query = array(), $auth = false, $header = array())
    {
        return $this->doRequest($endpoint, function($content) {
            return array(
                CURLOPT_CUSTOMREQUEST   => 'GET',
            );
        }, $query, null, $auth, $header);
    }
    
    public function sendPost($enpoint, $query = array(), $content = null, $auth = false, $header = array())
    {
        return $this->doRequest($endpoint, function($content) {
            return array(
                CURLOPT_CUSTOMREQUEST   => 'POST',
                CURLOPT_POSTFIELDS      => $content,
            );
        }, $query, $content, $auth, $header);
    }
    
    public function sendPostJson($endpoint, $content = null, $query = array(), $auth = false, $header = array())
    {
        return $this->doRequest($endpoint, function($content) {
            $json = json_encode($content);
            if (\json_last_error() != JSON_ERROR_NONE)
                return false;
            
            return array(
                CURLOPT_CUSTOMREQUEST   => 'POST',
                CURLOPT_POSTFIELDS      => $json,
            );
        }, $query, $content, $auth, array(
            'Content-Type'  => 'application/json',
        ));
    }
    
    public function sendPut($endpoint, $query = array(), $content = null, $auth = false, $header = array())
    {
        return $this->doRequest($endpoint, function($content) {
            return array(
                CURLOPT_CUSTOMREQUEST   => 'PUT',
                CURLOPT_POSTFIELDS      => $content,
            );
        }, $query, $content, $auth, $header);
    }
    
    public function sendPutJson($endpoint, $content = null, $query = array(), $auth = false, $header = array())
    {
        return $this->doRequest($endpoint, function($content) {
            $json = json_encode($content);
            if (\json_last_error() != JSON_ERROR_NONE)
            return false;
            
            return array(
                CURLOPT_CUSTOMREQUEST   => 'PUT',
                CURLOPT_POSTFIELDS      => $json,
            );
        }, $query, $content, $auth, array(
            'Content-Type'  => 'application/json',
        ));
    }
    
    public function sendDelete($endpoint, $query = array(), $auth = false, $header = array())
    {
        return $this->doRequest($endpoint, function($content) {
            return array(
                CURLOPT_CUSTOMREQUEST   => 'DELETE',
            );
        }, $query, null, $auth, $header);
    }
    
    /* =========================================================================
     * ~~ Public Helper Functions
     * =======================================================================*/
    
    public function getUserID($channel)
    {
        if (is_numeric($channel)) {
            try {
                return intval($channel);
            } catch (Exception $e) {}
        }
        
        if (is_array($channel) && isset($channel['_id']))
            return $channel['_id'];
            
        if (is_object($channel) && isset($channel->_id))
            return $channel->_id;
        
        $res = $this->sendGet('users', array(
            'login' => $channel,
        ));
        
        if (!$res || !isset($res->users) || !is_array($res->users))
            return false;
        
        return $res->users[0]->_id;
    }
    
    public function getPostID($post)
    {
        return $this->getGenericID($post);
    }
    
    public function getCommentID($comment)
    {
        return $this->getGenericID($comment);
    }
    
    public function getEmoteID($emote)
    {
        if (is_numeric($channel)) {
            try {
                return intval($channel);
            } catch (Exception $e) {}
        }
        
        if (is_array($object) && isset($object['image_id']))
            return $object['image_id'];
        
        if (is_object($object) && isset($object->image_id))
            return $object->image_id;
        
        $item = Emotes::get($emote);
        var_dump($item);
        return (!$item) ? false : $item['image_id'];
    }
    
    public function getCommunityID($community)
    {
        if (is_array($object) && isset($object['_id']))
            return $object['_id'];
        
        if (is_object($object) && isset($object->_id))
            return $object->_id;
        
        if (preg_match($this->community_id_pattern, $community))
            return $community;
        
        return false;
    }
    
    /* =========================================================================
     * ~~ Private Helper Functions
     * =======================================================================*/
    
    private function getGenericID($object)
    {
        if (is_numeric($channel)) {
            try {
                return intval($channel);
            } catch (Exception $e) {}
        }
        
        if (is_array($object) && isset($object['id']))
            return $object['id'];
        
        if (is_object($object) && isset($object->id))
            return $object->id;
        
        return false;
    }
    
    private function doRequest($url, $settings, $query = array(), $content = null, $auth = false, $header = array())
    {
        $url = $this->applyQuery($url, $query);
        $header = $this->applyHeader($header, $auth);
        $settings = call_user_func($settings, $content);
        if (!$url || !$header || !$settings)
            return false;
        
        $set = array(
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_FRESH_CONNECT   => true,
            CURLOPT_HEADER          => false,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => $header,
        );
        foreach ($set as $opt => $val) {
            $settings[$opt] = $val;
        }
        
        $curl = curl_init($url);
        curl_setopt_array($curl, $settings);
        $response = curl_exec($curl);
        $error = curl_errno($curl);
        curl_close($curl);
        
        if (!$response || $error > 0)
            return false;
        
        return $this->handleResponse($response);
    }
    
    private function applyQuery($url, $query)
    {
        $url = $this->removeQuery($this->base_url . $url);
        if (!$url)
            return false;
        
        return $url . '?' . http_build_query($query);
    }
    
    private function removeQuery($url)
    {
        $content = parse_url($url);
        if ($content === false)
            return false;
        
        $build = '';
        if (isset($content['scheme']))
            $build .= $content['scheme'] . '://';
        
        if (isset($content['user'])) {
            $build .= $content['user'];
            if (isset($content['pass']))
                $build .= ':' . $content['pass'];
            
            $build .= '@';
        }
        
        if (isset($content['host']))
            $build .= $content['host'];
        
        if (isset($content['port']))
            $build .= ':' . $content['port'];
        
        if (isset($content['path']))
            $build .= $content['path'];
        
        return $build;
    }
    
    private function applyHeader($header, $auth = false)
    {
        if ($auth && !isset($this->oauth_token))
            return false;
        
        $default = array(
            'Accept'    => 'application/vnd.twitchtv.v5+json',
            'Client-ID' => $this->client_id,
        );
        
        if ($auth)
            $default['Authorization'] = 'OAuth ' . $this->oauth_token;
        
        $header = array_merge($header, $default);
        
        $build = array();
        foreach ($header as $key => $val) {
            $build[] = $key . ': ' . $val;
        }
        return $build;
    }
    
    private function handleResponse($response)
    {
        $json = \json_decode($response);
        if (\json_last_error() != JSON_ERROR_NONE || isset($json->error))
            return false;
        
        return $json;
    }
}
