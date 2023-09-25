<?php

namespace Looqa\Dic\Exceptions;

use Exception;
use Throwable;

class ClosureResolveException extends Exception
{
    public function __construct(Throwable $previous)
    {
        parent::__construct($previous->getMessage(), $previous->getCode(), $previous);
    }
}