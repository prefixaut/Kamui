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
