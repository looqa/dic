<?php

namespace Looqa\Dic\Resolvers;

use Closure;
use Exception;
use Looqa\Dic\Enums\ResolutionMode;
use Looqa\Dic\Exceptions\ClosureResolveException;

final class ClosureResolver extends Resolver
{
    private mixed $resolvedInstance = null;
    private Closure $_closure;
    public function closure(Closure $closure): self
    {
        $this->_closure = $closure;
        return $this;
    }

    /**
     * @throws ClosureResolveException
     */
    public function resolve(): mixed {
        try {
            return match ($this->mode) {
                ResolutionMode::Singleton => $this->resolvedInstance ?? $this->resolvedInstance = $this->_closure->call($this),
                ResolutionMode::Factory => $this->_closure->call($this)
            };
        } catch (Exception $exception) {
            throw new ClosureResolveException($exception);
        }
    }
}