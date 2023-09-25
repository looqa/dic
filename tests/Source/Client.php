<?php

namespace Looqa\Dic\Tests\Source;

use Looqa\Dic\Traits\UsesDependencyContainer;

class Client
{
    use UsesDependencyContainer;
    private $dependency;
    public function __construct(IDependency $dependency)
    {
        $this->dependency = $dependency;
    }

    public function work(): int {
        return $this->dependency->getParam();
    }
}