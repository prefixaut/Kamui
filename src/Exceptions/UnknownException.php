<?php

namespace Kamui\Exceptions;

class UnknownException extends \Exception
{
    public function __construct($msg = null)
    {
        $def = 'Unknown Twitch-Exception/Response';
        if (is_string($msg)) {
            $msg = $def . ': ' . $msg;
        } else {
            $msg = $def . '.';
        }
        parent::__construct($msg);
    }
    
    public function __toString()
    {
        return get_class($this) . " '{$this->message}' in {$this->file}({$this->line})\n{$this->getTraceAsString()}";
    }
}
