<?php

namespace Looqa\Dic;

use Looqa\Dic\Exceptions\DependencyOverwriteException;
use Looqa\Dic\Exceptions\ResolveNotFoundException;
use Looqa\Dic\Interfaces\IResolver;

class Container
{
    /**
     * @var IResolver[]
     */
    private array $dependencies = [];

    private static ?self $_instance = null;

    /**
     * @param IResolver[]|null $initialDependencies
     * @return static
     */
    public static function make(?array $initialDependencies = null): static {
        $instance = static::$_instance ?? new self();
        if ($initialDependencies) {
            foreach ($initialDependencies as $dependency => $resolver) {
               $instance->dependencies[$dependency] = $resolver;
            }
        }
        if (!static::$_instance)
            static::$_instance = $instance;
        return static::$_instance;
    }

    /**
     * @return self
     */
    public static function instance(): static {
        if (!static::$_instance) {
            static::$_instance = static::make();
        }
        return static::$_instance;
    }

    /**
     * @throws ResolveNotFoundException
     */
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new ResolveNotFoundException($id);
        }
        return $this->dependencies[$id]->resolve();

    }

    public function has(string $id): bool
    {
        if (isset($this->dependencies[$id]) || array_key_exists($id, $this->dependencies)) {
            return true;
        }
        return false;
    }

    /**
     * @throws DependencyOverwriteException
     */
    public function add(string $id, IResolver $resolver, bool $overwrite = false): void {
        if (!$overwrite && $this->has($id))
            throw new DependencyOverwriteException($id);
        $this->dependencies[$id] = $resolver;
    }

    public static function destroy(): void {
        self::$_instance = null;
    }
}