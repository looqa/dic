<?php

namespace Looqa\Dic\Tests\Source;

class ConcreteDependency implements IDependency
{
    private int $param;
    public function __construct(int $param)
    {
        $this->param = $param;
    }

    public function getParam(): int
    {
        return $this->param;
    }
}