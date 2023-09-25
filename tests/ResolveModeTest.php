<?php

namespace Looqa\Dic\Tests;

use Looqa\Dic\Container;
use Looqa\Dic\Enums\ResolutionMode;
use Looqa\Dic\Resolvers\ClosureResolver;
use Looqa\Dic\Tests\Source\ConcreteDependency;
use Looqa\Dic\Tests\Source\IDependency;
use PHPUnit\Framework\TestCase;

class ResolveModeTest extends TestCase
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

    public function testThatSingletonDependencyIsSingleton() {
        Container::instance()->add(IDependency::class, ClosureResolver::make(ResolutionMode::Singleton)->closure(function () {
            srand((double)microtime()*1000000);
            return new ConcreteDependency(rand(0, 1000000));
        }));
        $result1 = Container::instance()->get(IDependency::class)->getParam();
        $result2 = Container::instance()->get(IDependency::class)->getParam();
        $this->assertEquals($result1, $result2);
    }

    public function testThatFactoryDependencyIsFactory() {
        Container::instance()->add(IDependency::class, ClosureResolver::make(ResolutionMode::Factory)->closure(function () {
            srand((double)microtime()*1000000);
            return new ConcreteDependency(rand(0, 1000000));
        }));
        $result1 = Container::instance()->get(IDependency::class)->getParam();
        $result2 = Container::instance()->get(IDependency::class)->getParam();
        $this->assertNotEquals($result1, $result2);
}
}
