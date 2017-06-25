<?php

namespace Kamui;

use Kamui\Resource;
use Kamui\Util\APICache;
use Kamui\Util\MemoryCache;
use Kamui\Helpers\Emotes;
use Kamui\Resources\Bits;
use Kamui\Resources\ChannelFeed;
use Kamui\Resources\Channels;
use Kamui\Resources\Chat;
use Kamui\Resources\Clips;
use Kamui\Resources\Collections;
use Kamui\Resources\Communities;
use Kamui\Resources\Games;
use Kamui\Resources\Ingests;
use Kamui\Resources\Search;
use Kamui\Resources\Streams;
use Kamui\Resources\Teams;
use Kamui\Resources\Users;
use Kamui\Resources\Videos;
use Kamui\Resources\VHS;

class API
{
    /* =========================================================================
     * ~~ Variables
     * =======================================================================*/
     
    // Authentication
    private $client_id;
    private $client_secret;
    private $oauth_token;
    
    // Basic
    private $resources = array();
    private $base_url = 'https://api.twitch.tv/kraken/';
    private $auth_url = 'https://api.twitch.tv/kraken/oauth2/authorize';
    
    // Patterns
    private $community_id_pattern = '/^[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/';
    private $collection_id_pattern = '/^[a-zA-Z0-9]{14}$/';
    private $collection_item_id_pattern = '/[a-zA-Z0-9]{44}$/';
    private $video_id_pattern = '/^[v]?[0-9]+/';
    
    // Cache
    private $cache;
    
    /* =========================================================================
     * ~~ Constructor
     * =======================================================================*/
    
    public function __construct($client_id, $client_secret = null, $oauth_token = null, APICache $cache = null)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->oauth_token = $oauth_token;
        $this->cache = is_null($cache) ? new MemoryCache() : $cache;
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
    
    public function __set($name, $value)
    {
        if (!is_string($name) || !($name instanceof Resource))
            return;
        
        $this->resources[$name] = $value;
    }
    
    public function __isset($name)
    {
        return isset($this->resources[$name]);
    }
    
    public function __unset($name)
    {
        if (isset($this->resources[$name]))
            unset($this->resources[$name]);
    }
    
    /* =========================================================================
     * ~~ Getters and Setters
     * =======================================================================*/
    
    public function getClientID()
    {
        return $this->client_id;
    }
    
    public function setClientID($id)
    {
        $this->client_id = $id;
    }
    
    public function getClientSecret()
    {
        return $this->client_secret;
    }
    
    public function setClientSecret($secret)
    {
        $this->client_secret = $secret;
    }
    
    public function getOAuthToken()
    {
        return $this->oauth_token;
    }
    
    public function setOAuthToken($token)
    {
        $this->oauth_token = $token;
    }
    
    public function getCache()
    {
        return $this->cache;
    }
    
    public function setCache(APICache $cache)
    {
        $this->cache = $cache;
    }
    
    /* =========================================================================
     * ~~ Setup Functions
     * =======================================================================*/
    
    private function setupResources()
    {
        $this->resources['bits'] = new Bits($this);
        $this->resources['feed'] = new ChannelFeed($this);
        $this->resources['channels'] = new Channels($this);
        $this->resources['chat'] = new Chat($this);
        $this->resources['clips'] = new Clips($this);
        $this->resources['collections'] = new Collections($this);
        $this->resources['communites'] = new Communities($this);
        $this->resources['games'] = new Games($this);
        $this->resources['ingests'] = new Ingests($this);
        $this->resources['search'] = new Search($this);
        $this->resources['streams'] = new Streams($this);
        $this->resources['teams'] = new Teams($this);
        $this->resources['users'] = new Users($this);
        $this->resources['videos'] = new Videos($this);
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
    
    public function getAuthentificationUrl($redirect, $type, $scopes, $args = array())
    {
        $scopes = is_array($scopes) ? implode($scopes) : $scopes;
        $query = array(
            'client_id'     => $this->client_id,
            'redirect_uri'  => $redirect,
            'response_type' => $type,
            'scope'         => $scopes,
        );
        
        return $this->auth_url . '?' . http_build_query(array_merge($args, $query));
    }
    
    public function getUserID($user, $fetch = true)
    {
        $id = $this->getGenericID($user, '_id');
        if ($id) {
            if (is_object($user)) {
                $this->cache->set('user_' . strtolower($user->name), $id);
            } elseif (is_array($user)) {
                $this->cache->set('user_' . strtolower($user['name']), $id);
            }
            
            return $id;
        }
        
        $id = $this->cache->get('user_' . strtolower($user));
        if ($id)
            return $id;
        
        if (!$fetch)
            return false;
        
        $res = $this->sendGet('users', array(
            'login' => $user,
        ));
        
        if (!$res || !isset($res->users) || !is_array($res->users))
            return false;
        
        $id = $res->users[0]->_id;
        $this->cache->set('user_' . strtolower($user), $id);
        return $id;
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
        $id = $this->getGenericID($emote, 'image_id');
        if ($id)
            return $id;
        
        $item = Emotes::get($emote);
        return (!$item) ? false : $item['image_id'];
    }
    
    public function getCommunityID($community, $fetch = true)
    {
        $id = $this->getPatternID($community, $this->community_id_pattern);
        if ($id) {
            if (is_object($community)) {
                $this->cache->set('community_' . strtolower($community->name, $id));
            } elseif (is_array($community)) {
                $this->cache->set('community_' . strtolower($community['name'], $id));
            }
            return $id;
        }
        
        $id = $this->cache->get('community_' . strtolower($community));
        if ($id)
            return $id;
        
        if (!$fetch)
            return false;
        
        $res = $this->api->sendGet('communities', $args = array(
            'name'  => $community,
        ));
        
        if (!$res)
            return false;
        
        $this->cache->set('community_' . strtolower($community), $res->_id);
        return $res->_id;
    }
    
    public function getCollectionID($collection)
    {
        return $this->getPatternID($collection, $this->collection_id_pattern);
    }
    
    public function getVideoID($video)
    {
        $id = $this->getPatternID($video, $this->video_id_pattern);
        if ($id && preg_match('/^v/', $id)) {
            $id = substr($id, 1);
        }
        return $id;
    }
    
    public function getCollectionItemID($item)
    {
        return $this->getPatternID($item, $this->collection_item_id_pattern);
    }
    
    public function getCollectionItemType($item, $field = 'item_type', $default = 'video')
    {
        if (is_array($item) && isset($item[$field]))
            return $item[$field];
        
        if (is_object($item) && isset($item->{$field}))
            return $item->{$field};
        
        return 'video';
    }
    
    /* =========================================================================
     * ~~ Private Helper Functions
     * =======================================================================*/
    
    private function getGenericID($object, $field = 'id', $number = true)
    {
        if ($object === false)
            return false;
        
        if ($number && is_numeric($object)) {
            try {
                return intval($object);
            } catch (Exception $e) {}
        }
        
        if (is_array($object) && isset($object[$field]))
            return $object[$field];
        
        if (is_object($object) && isset($object->{$field}))
            return $object->{$field};
        
        return false;
    }
    
    private function getPatternID($object, $pattern, $field = '_id')
    {
        $id = $this->getGenericID($object, $field, false);
        if ($id)
            return $id;
        
        if (is_string($object) && preg_match($pattern, $object))
            return $object;
        
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
        
        if ($error > 0)
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
        if (empty($response) || is_null($response))
            return null;
        
        $json = \json_decode($response);
        if (\json_last_error() != JSON_ERROR_NONE || isset($json->error))
            return false;
        
        return $json;
    }
}
