<?php

namespace Looqa\Dic\Exceptions;

use Exception;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class ResolveNotFoundException extends Exception
{
    private string $failedDependency;

    public function __construct(string $failedDependency)
    {
        $this->failedDependency = $failedDependency;
        parent::__construct("Dependency not found in container: $failedDependency");
    }

    public function getFailedDependency(): string {
        return $this->failedDependency;
    }
}