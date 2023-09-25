<?php

namespace Looqa\Dic\Tests;

use Exception;
use Looqa\Dic\Container;
use Looqa\Dic\Enums\ResolutionMode;
use Looqa\Dic\Exceptions\ClosureResolveException;
use Looqa\Dic\Resolvers\ClosureResolver;
use Looqa\Dic\Tests\Source\Client;
use Looqa\Dic\Tests\Source\ConcreteDependency;
use Looqa\Dic\Tests\Source\IDependency;
use PHPUnit\Framework\TestCase;

class ClosureResolverTest extends TestCase
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

    public function testResolverThrowsExpectionWhenClosureNotValid() {
        $this->expectException(ClosureResolveException::class);
        Container::instance()->add(IDependency::class, ClosureResolver::make(ResolutionMode::Singleton)->closure(function () {
            throw new Exception("Some exception.");
        }));
        Client::withDependencies();
    }
}
