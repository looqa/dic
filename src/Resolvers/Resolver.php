<?php

namespace Looqa\Dic\Resolvers;

use Looqa\Dic\Enums\ResolutionMode;
use Looqa\Dic\Interfaces\IResolver;

abstract class Resolver implements IResolver
{
    protected ResolutionMode $mode;
     public static function make(ResolutionMode $mode): static
     {
         $new = new static();
         $new->mode = $mode;
         return $new;
     }

    abstract public function resolve(): mixed;
}