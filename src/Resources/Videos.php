<?php

namespace Kamui\Resources;

use Kamui\API;
use Kamui\Resource;
use Kamui\Helpers\Uploader;

class Videos extends Resource
{
    public function __construct(API $api)
    {
        parent::__construct($api);
    }
    
    public function get($video)
    {
        $id = $this->api->getVideoID($video);
        if (!$id)
            return false;
        
        return $this->api->sendGet("videos/{$id}");
    }
    
    public function top($args = array())
    {
        return $this->api->sendGet('videos/top', $args);
    }
    
    public function followed($args = array())
    {
        return $this->api->sendGet('videos/followed', $args, true);
    }
    
    public function create($channel, $title, $args = array())
    {
        $id = $this->api->getUserID($channel);
        if (!$id || is_null($title))
            return false;
        
        $args = array_merge($args, array(
            'channel_id'    => $id,
            'title'         => $title,
        ));
        
        return $this->api->sendPost('videos', $args, null, true);
    }
    
    public function upload($channel, $title, $file, $args = array())
    {
        $uploader = new Uploader($this->api);
        return $uploader->uploadVideo($channel, $title, $file, $args);
    }
    
    public function update($video, $args = array())
    {
        $id = $this->api->getVideoID($video);
        if (!$id)
            return false;
        
        return $this->api->sendPut("videos/{$id}", $args, null, true);
    }
    
    public function delete($video)
    {
        $id = $this->api->getVideoID($video);
        if (!$id)
            return false;
        
        return $this->api->sendDelete("videos/{$id}", array(), true);
    }
}
