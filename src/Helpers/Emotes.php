<?php

namespace Kamui\Helpers;

class Emotes
{
    private static $last_fetch = null;
    private static $cache = null;
    
    public static function get($emote)
    {
        $data = self::fetch();
        if (!$data) {
            echo "no data";
            return false;
        }
        
        return (isset($data[$emote])) ? $data[$emote] : false;
    }
    
    private static function fetch()
    {
        if (!is_null(self::$last_fetch) && !is_null(self::$cache) && self::$last_fetch->diff(new \DateTime())->i <= 30)
            return self::$cache;
        
        $content = @\file_get_contents("https://twitchemotes.com/api_cache/v2/global.json");        
        $json = \json_decode($content, true);
        if (!$json || \json_last_error() != JSON_ERROR_NONE) {
            echo "JSON ERROR: " . \json_last_error_msg();
            return false;
        }
        
        self::$last_fetch = new \DateTime($json['meta']['generated_at']);
        self::$cache = $json['emotes'];
        return $json['emotes'];
    }
}
