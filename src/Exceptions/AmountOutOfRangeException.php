<?php

namespace PragmaGoTech\Interview\Exceptions;

class AmountOutOfRangeException extends \Exception
{
    public function __construct($message = 'Amount is out of range', $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}