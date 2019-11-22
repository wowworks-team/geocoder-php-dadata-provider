<?php

namespace Wowworks\Dadata\Exception;

class NotImplementedException extends \RuntimeException
{
    public function __construct($message = "This method is not implemented.")
    {
        parent::__construct($message);
    }
}
