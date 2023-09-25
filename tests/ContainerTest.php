<?php

namespace Looqa\Dic\Tests;
use Looqa\Dic\Container;
use Looqa\Dic\Enums\ResolutionMode;
use Looqa\Dic\Exceptions\DependencyOverwriteException;
use Looqa\Dic\Exceptions\ResolveNotFoundException;
use Looqa\Dic\Resolvers\ClosureResolver;
use Looqa\Dic\Tests\Source\Client;
use Looqa\Dic\Tests\Source\ConcreteDependency;
use Looqa\Dic\Tests\Source\IDependency;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    protected function setUp(): void
    {
        Container::make();
        parent::setUp();
    }

    protected function tearDown(): void
    {
        Container::destroy();
        parent::tearDown();
    }

    public function testContainerCreatesSuccessfully() {
        Container::instance()->add(IDependency::class, ClosureResolver::make(ResolutionMode::Singleton)->closure(function () {
            return new ConcreteDependency(1);
        }));
        $client = Client::withDependencies();
        $dependency = Container::instance()->get(IDependency::class);
        $this->assertEquals($client->work(), $dependency->getParam());
        $this->assertTrue(Container::instance()->has(IDependency::class));
        $this->assertTrue(Container::instance()->get(IDependency::class) instanceof IDependency);
    }

    public function testContainerThrowsExceptionWhenDependencyNotSet() {
        $this->expectException(ResolveNotFoundException::class);
        Client::withDependencies();
    }

    public function testContainerThrowsExceptionWhenDependencyAlreadySet() {
        $this->expectException(DependencyOverwriteException::class);
        Container::instance()->add(IDependency::class, ClosureResolver::make(ResolutionMode::Singleton)->closure(function () {
            return new ConcreteDependency(1);
        }));
        Container::instance()->add(IDependency::class, ClosureResolver::make(ResolutionMode::Singleton)->closure(function () {
            return new ConcreteDependency(1);
        }));
    }

    public function testContainerSuccessfullyOverwritesDependency() {
        Container::instance()->add(IDependency::class, ClosureResolver::make(ResolutionMode::Singleton)->closure(function () {
            return new ConcreteDependency(1);
        }));
        $this->assertEquals(1, Container::instance()->get(IDependency::class)->getParam());
        Container::instance()->add(IDependency::class, ClosureResolver::make(ResolutionMode::Singleton)->closure(function() {
            return new ConcreteDependency(2);
        }), true);
        $this->assertEquals(2, Container::instance()->get(IDependency::class)->getParam());
    }
}
