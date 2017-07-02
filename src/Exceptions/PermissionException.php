<?php

namespace Kamui\Exceptions;

class PermissionException extends \Exception
{
    public function __construct($scope)
    {
        parent::__construct("The provided Auth-Token does not include the required scope '{$scope}'");
    }
    
    public function __toString()
    {
        return get_class($this) . " '{$this->message}' in {$this->file}({$this->line})\n{$this->getTraceAsString()}";
    }
}
