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

class Uploader
{
    private $api;
    private $base_url = 'https://uploads.twitch.tv/';
    
    private $video_max_size = 1024 * 1024 * 25; // 25MB
    private $video_types = array(
        'video/mp4',
        'video/quicktime',
        'application/x-troff-msvideo',
        'video/avi',
        'video/msvideo',
        'video/x-msvideo',
        'video/x-flv',
    );
    
    public function __construct(Kamui\API $api)
    {
        $this->api = $api;
    }
    
    public function uploadVideo($channel, $title, $file, $args = array())
    {
        if (PHP_MAJOR_VERSION < 5 || (PHP_MAJOR_VERSION == 5 && PHP_MINOR_VERSION < 5)) {
            throw new \Exception("You cannot use this Feature on you PHP Version (" . phpversion() . "), required Version >=5.5|^7.0");
        }
        
        if (!is_readable($file))
            return false;
        
        $video = $this->api->videos->create($channel, $title, $args);
        if (!$video)
            return false;
        
        $mime_info = finfo_open(FILEINFO_MIME_TYPE);
        $mine = finfo_file($mime_info, $file);
        finfo_close($mime_info);
        $size = filesize($file);
        
        if ($size > $this->video_max_size) {
            $position = 0;
            
            while ($position + $this->video_max_size < $size) {
                $content = file_get_contents($file, false, null, $position, $this->video_max_size);
                $current_size = $size - $position;
                if ($current_size > $this->video_max_size)
                    $current_size = $video_max_size;
                
                if (!$this->uploadVideoContent($video, $mime, $current_size, $content))
                    return false;
                
                $position += $this->video_max_size;
            }
        } else {
            $content = file_get_contents($file);
            if (!$this->uploadVideoContent($video, $mime, $size, $content))
                return false;
        }
        
        return $this->completeVideo($video);
    }
    
    private function uploadVideoContent($video, $mime, $size, $content, $part = 1)
    {
        $url = $video->upload->url;
        $url .= '?' . http_build_query(array(
            'part'  => $part,
            'token' => $token,
        ));
        
        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST   => 'PUT',
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_FRESH_CONNECT   => true,
            CURLOPT_HEADER          => false,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => array(
                'Content-Length: ' + $size,
                'Content-Type: ' + $mime,
            ),
            CURLOPT_POSTFIELDS      => $content,
        ));
        $response = curl_exec($curl);
        $error = curl_errno($curl);
        curl_close($curl);
        
        if ($error > 0)
            return false;
        
        return true;
    }
    
    private function completeVideo($video)
    {
        $url = $video->upload->url;
        $url .= '/complete?' . http_build_query(array(
            'upload_token'  => $video->upload->token,
        ));
        
        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST   => 'POST',
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_FRESH_CONNECT   => true,
            CURLOPT_HEADER          => false,
            CURLOPT_RETURNTRANSFER  => true,
        ));
        curl_exec($curl);
        $error = curl_errno($curl);
        curl_close($curl);
        
        return $error == 0;
    }
}
