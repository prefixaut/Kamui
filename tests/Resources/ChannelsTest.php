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

namespace Kamui\Test\Resources;

use Kamui\Test\ResourceCase;
use Kamui\Exceptions\AuthentificationException;
use Kamui\Exceptions\UnknownException;

class ChannelsTest extends ResourceCase
{
    private $getFields = array(
        '_id',
        'broadcaster_language',
        'created_at',
        'display_name',
        'followers',
        'game',
        'language',
        'logo',
        'mature',
        'name',
        'partner',
        'profile_banner',
        'profile_banner_background_color',
        'status',
        'updated_at',
        'url',
        'video_banner',
        'views'
    );
    
    function testGetMe()
    {
        $data = $this->personal->channels->get();
        
        $this->assertNotFalse($data);
        $this->assertObjectHasProperties($data, array_merge($this->getFields, array(
            'email',
            'stream_key',
        )));
        $this->assertEquals($this->id, $data->_id);
        $this->assertEquals(strtolower($this->name), strtolower($data->name));
    }

    function testGetMeAuthentification()
    {
        $this->expectException(AuthentificationException::class);
        $this->public->channels->get();
    }
    
    function testGetByName()
    {
        $data = $this->public->channels->get($this->name);
        
        $this->assertNotFalse($data);
        $this->assertObjectHasProperties($data, $this->getFields);
        $this->assertEquals($this->id, $data->_id);
        $this->assertEquals(strtolower($this->name), strtolower($data->name));
    }
    
    function testGetByID()
    {
        $data = $this->public->channels->get($this->id);
        
        $this->assertNotFalse($data);
        $this->assertObjectHasProperties($data, $this->getFields);
        $this->assertEquals($this->id, $data->_id);
        $this->assertEquals(strtolower($this->name), strtolower($data->name));
    }

    function testGetExceptions()
    {
        $this->assertFalse($this->public->channels->get(NaN));
        $this->assertFalse($this->public->channels->get([]));
        $this->assertFalse($this->public->channels->get(new \stdClass));
        $this->assertFalse($this->public->channels->get(-1));
    }
    
    function testUpdate()
    {
        $data = $this->personal->channels->update($this->name, array(
            'channel_feed_enabled' => false,
        ));
        
        $this->assertNotFalse($data);
        $this->assertObjectHasProperties($data, $this->getFields);
        $this->assertEquals($this->id, $data->_id);
        $this->assertEquals(strtolower($this->name), strtolower($data->name));
        
        // Reset the changes
        $this->assertNotFalse($this->personal->channels->update($this->name, array(
            'channel_feed_enabled' => true,
        ))); 
        
        // Test that we don't have access to other channels
        $this->assertFalse($this->personal->channels->update($this->inaccessable, array(
            'status'    => 'this will fail anyways',
        )));

        $this->assertFalse($this->personal->channels->update(NaN, new \stdClass));
        $this->assertFalse($this->personal->channels->update(null, new \stdClass));
        $this->assertFalse($this->personal->channels->update([], new \stdClass));
        $this->assertFalse($this->personal->channels->update(new \stdClass, new \stdClass));
        $this->assertFalse($this->personal->channels->update(-1, new \stdClass));
    }

    function testUpdateAuthentification()
    {
        $this->expectException(AuthentificationException::class);
        $this->public->channels->update($this->name, array(
            'status'    => 'this will fail as well',
        ));
    }
    
    function testEditors()
    {
        $data = $this->personal->channels->editors($this->name);
        
        $this->assertNotFalse($data);
        $this->assertTrue(isset($data->users));
        $this->assertInternalType('array', $data->users);

        $this->assertFalse($this->personal->channels->editors(NaN));
        $this->assertFalse($this->personal->channels->editors(null));
        $this->assertFalse($this->personal->channels->editors([]));
        $this->assertFalse($this->personal->channels->editors(new \stdClass));
        $this->assertFalse($this->personal->channels->editors(-1));
    }

    function testEditorsAuthentification()
    {
        $this->expectException(AuthentificationException::class);
        $this->public->channels->editors($this->name);
    }
    
    function testFollowers()
    {
        $data = $this->public->channels->followers($this->name);
        
        $this->assertNotFalse($data);
        $this->assertObjecthasProperties($data, array('_cursor', '_total', 'follows'));
        $this->assertInternalType('array', $data->follows);

        $this->assertFalse($this->public->channels->followers(NaN));
        $this->assertFalse($this->public->channels->followers(null));
        $this->assertFalse($this->public->channels->followers([]));
        $this->assertFalse($this->public->channels->followers(new \stdClass));
        $this->assertFalse($this->public->channels->followers(-1));
    }
    
    function testTeams()
    {
        $data = $this->public->channels->teams($this->name);
        
        $this->assertNotFalse($data);
        $this->assertTrue(isset($data->teams));
        $this->assertInternalType('array', $data->teams);

        $this->assertFalse($this->public->channels->teams(NaN));
        $this->assertFalse($this->public->channels->teams(null));
        $this->assertFalse($this->public->channels->teams([]));
        $this->assertFalse($this->public->channels->teams(new \stdClass));
        $this->assertFalse($this->public->channels->teams(-1));
    }
    
    /*
    TODO: Find way to these this stuff

    function testSubscribers()
    {
        $data = $this->personal->channels->subscribers($this->name);
        
        $this->assertNotFalse($data);
        $this->assertTrue(isset($data->subscriptions));
        $this->assertInternalType('array', $data->subscriptions);
    }

    
    function testSubscriber()
    {
        $data = $this->personal->channels->subscriber('kuruhs', $this->name);

        $this->assertNotFalse($data);
        $this->assertTrue(isset($data->_id));
        $this->assertInternalType('array', $data->user);
    }
    */

    function testVideos()
    {
        $data = $this->public->channels->videos($this->name);

        $this->assertNotFalse($data);
        $this->assertObjectHasProperties($data, array('_total', 'videos'));
        $this->assertInternalType('array', $data->videos);

        $this->assertFalse($this->public->channels->videos(NaN));
        $this->assertFalse($this->public->channels->videos(null));
        $this->assertFalse($this->public->channels->videos([]));
        $this->assertFalse($this->public->channels->videos(new \stdClass));
        $this->assertFalse($this->public->channels->videos(-1));
    }

    function testStartCommercial()
    {
        $data = $this->personal->channels->startCommercial($this->name, 30);

        $this->assertNotFalse($data);
        $this->assertObjectHasProperties($data, array('Length', 'Message', 'RetryAfter'));

        $this->assertFalse($this->personal->channels->startCommercial(NaN, 30));
        $this->assertFalse($this->personal->channels->startCommercial(null, 30));
        $this->assertFalse($this->personal->channels->startCommercial([], 30));
        $this->assertFalse($this->personal->channels->startCommercial(new \stdClass, 30));
        $this->assertFalse($this->personal->channels->startCommercial(-1, 30));
    }

    function testStartCommercialAuthentification()
    {
        $this->expectException(AuthentificationException::class);
        $this->public->channels->startCommercial($this->name, 30);
    }

    function testStartCommercialInvalidDuration()
    {
        /*
            // Should be thrown according to documentation
        $this->expectException(UnknownException::class);
        $this->personal->channels->startCommercial($this->name, 0);
        */
        // Dummy test to prevent phpunit to cry about it
        $this->assertTrue(true);
    }

    /*
        TODO: Tests for:
            - Reset Streamkey
            - Set Community
            - Delete Community
        on new Account for it
    */
}
