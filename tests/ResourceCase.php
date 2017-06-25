<?php

namespace Kamui\Test;

use PHPUnit\Framework\TestCase;
use Kamui\API;

class ResourceCase extends TestCase
{
    protected $api;
    private $root;
    
    public function setUp()
    {
        $root = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
        $client_id = $this->getVaribale('TWITCH_CLIENT_ID', 'client_id.txt');
        $secret = $this->getVaribale('TWITCH_CLIENT_SECRET', 'secret.txt');
        $oauth = $this->getVaribale('TWITCH_OAUTH_TOKEN', 'oauth.txt');
        
        $this->api = new API($client_id, $secret, $oauth);
    }
    
    private function getVaribale($env, $file)
    {
        if (isset($_ENV[$env]))
            return $_ENV[$env];
        
        if (is_readable($this->root . $file))
            return @file_get_contents($this->root . $file);
        
        return null;
    }
}
