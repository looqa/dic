<?php

namespace Looqa\Dic\Interfaces;

use Looqa\Dic\Enums\ResolutionMode;

interface IResolver
{
    public function resolve(): mixed;

    public static function make(ResolutionMode $mode): self;
}