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
use Kamui\Resource;

/**
 * Resource for the Bits Endpoint.
 *
 * @see https://dev.twitch.tv/docs/v5/reference/bits/
 */
class Bits extends Resource
{
    public function __construct(API $api)
    {
        parent::__construct($api);
    }
    
    /**
     * Retrieves the list of available cheermotes, animated emotes to which
     * viewers can assign bits, to cheer in chat. The cheermotes returned are
     * available throughout Twitch, in all bits-enabled channels.
     * 
     * @param string|integer|object|array|null $channel The Channel-ID or Channel-Object from where it should retrieve it (optional)
     * @return false|object Object returned from the Twitch-API or false on failure
     * 
     * @throws \Kamui\Exceptions\InvalidRequestException When Exceptions are enabled and the request was invalid
     * @throws \Kamui\Exceptions\PermissionException When Exceptions are enabled and the endpoint requires permissions that aren't met
     * @throws \Kamui\Exceptions\AuthentificationException When Exceptions are enabled and the endpoint requires authentification (user-permission)
     *
     * @see https://dev.twitch.tv/docs/v5/reference/bits/#get-cheermotes
     */
    public function get($channel = null)
    {
        $args = array();
        if (!is_null($channel)) {
            $id = $this->api->getUserID($channel);
            if (!$id)
                return false;
            
            $args['channel_id'] = $id;
        }
        
        return $this->api->sendGet('bits/actions', $args);
    }
}
