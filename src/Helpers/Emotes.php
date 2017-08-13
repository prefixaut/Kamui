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

use Stash\Pool;
use Stash\Driver\FileSystem;

class Emotes
{

    private static $cache = new Pool(new FileSystem());
    private static $key = 'emote_list';
    
    public static function get($emote)
    {
        $data = self::fetch();
        if (!$data) {
            return false;
        }
        
        return (isset($data[$emote])) ? $data[$emote] : false;
    }

    private static function fetch()
    {
        $item = self::$cache->getItem(self::$key);
        $data = $item->get();
        if (!$item->isMiss()) {
            return $data;
        }
        
        try {
            $content = \file_get_contents("https://twitchemotes.com/api_cache/v3/global.json");
        } catch (\Exception $e) {
            echo 'Could not fetch the emote-data from twitchemotes-api';
            return false;
        }
        $json = \json_decode($content, true);
        if (!$json || \json_last_error() != JSON_ERROR_NONE) {
            echo 'Json-Error from the twitchemotes-api: ' . \json_last_error_msg();
            return false;
        }
        
        $item->set($json);
        $cache->save($item);
        return $json;
    }
}
