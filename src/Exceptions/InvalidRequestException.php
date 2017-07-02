<?php

namespace Kamui\Exceptions;

class InvalidRequestException extends \Exception
{
    public function __construct($msg = null)
    {
        parent::__construct($msg);
    }
    
    public function __toString()
    {
        return get_class($this) . " '{$this->message}' in {$this->file}({$this->line})\n{$this->getTraceAsString()}";
    }
}
