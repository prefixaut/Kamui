<?php

namespace Kamui\Resources;

use Kamui\API;
use Kamui\Resource;

class Chat extends Resource
{
    public function __construct(API $api)
    {
        parent::__construct($api);
    }
    
    public function badges($channel)
    {
        $id = $this->api->getUserID($channel);
        if (!$id)
            return false;
        
        return $this->api->sendGet("chat/{$id}/badges");
    }
    
    public function emotes($images = false, $emotes = array())
    {
        if ($images)
            return $this->api->sendGet('chat/emoticons');
        
        $args = array();
        if (!empty($emotes)) {
            $args = array(
                'emotesets' => $emotes,
            );
        }
        
        return $this->api->sendGet('emoticon_images', $args);
    }
}
