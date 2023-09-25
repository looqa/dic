<?php

namespace Looqa\Dic\Traits;

use Looqa\Dic\Container;
use Looqa\Dic\Exceptions\ResolveNotFoundException;
use ReflectionClass;

trait UsesDependencyContainer
{
    /**
     * @throws ResolveNotFoundException
     */
    public static function withDependencies(...$args): static
    {
        $targetReflection = new ReflectionClass(static::class);
        $targetConstructor = $targetReflection->getConstructor();
        $parameters = $targetConstructor->getParameters();

        if (count($parameters) == count($args))
            return new static($args);

        $resolvedArgs = [];
        foreach ($parameters as $parameter) {
            $parameterType = $parameter->getType();
            if (!$parameterType->isBuiltin()) {
                $parameterName = $parameterType->getName();
                $resolvedArgs[] = Container::instance()->get($parameterName);
            }
        }

        return new static(...array_merge($resolvedArgs, $args));
    }
}