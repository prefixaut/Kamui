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

class Videos extends Kamui\BaseResource
{
    public function __construct(Kamui\API $api)
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
        $this->api->scope = 'user_read';
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
        
        $this->api->scope = 'channel_editor';
        return $this->api->sendPost('videos', $args, null, true);
    }
    
    public function upload($channel, $title, $file, $args = array())
    {
        $uploader = new Kamui\Uploader($this->api);
        return $uploader->uploadVideo($channel, $title, $file, $args);
    }
    
    public function update($video, $args = array())
    {
        $id = $this->api->getVideoID($video);
        if (!$id)
            return false;
        
        $this->api->scope = 'channel_editor';
        return $this->api->sendPut("videos/{$id}", $args, null, true);
    }
    
    public function delete($video)
    {
        $id = $this->api->getVideoID($video);
        if (!$id)
            return false;
        
        $this->api->scope = 'channel_editor';
        return $this->api->sendDelete("videos/{$id}", array(), true);
    }
}
