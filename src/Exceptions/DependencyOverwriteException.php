<?php

namespace Looqa\Dic\Exceptions;

use Exception;

class DependencyOverwriteException extends Exception
{
    public function __construct(string $dependency)
    {
        parent::__construct("Trying to overwrite existing $dependency with overwrite flag = false.");
    }
}