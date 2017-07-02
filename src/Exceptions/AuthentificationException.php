<?php

namespace Kamui\Exceptions;

class AuthentificationException extends \Exception
{
    public function __construct()
    {
        parent::__construct('The resource required Authentification, but none was provided in the API');
    }
    
    public function __toString()
    {
        return get_class($this) . " '{$this->message}' in {$this->file}({$this->line})\n{$this->getTraceAsString()}";
    }
}
