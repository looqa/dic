# PHP Dependency Injection Container

This library provides a simple implementation of the Dependency Injection Container (DIC) pattern. The container behaves as a singleton object, allowing creation and initialization at a single point in your application. Subsequently, you can add or replace dependencies as needed.

## Usage Example

Install library:
`composer require looqa/dic`

Firstly, include the autoloader and necessary classes:

```php
require "vendor/autoload.php";

use Looqa\Dic\Container;
use Looqa\Dic\Enums;
use Looqa\Dic\Resolvers;
```

Next, define your dependencies by using `Container::make([])`. Here, we're setting up a few different dependencies by using the `ClosureResolver`:

```php
Container::make([
    ISomeDependency::class => ClosureResolver::make(ResolutionMode::Factory)->closure(function() {
        return new ConcreteDependency();
    }),
    DependentDependency::class => ClosureResolver::make(ResolutionMode::Factory)->closure(function() {
        $neededDependency = Container::instance()->get(ConcreteDependency::class);
        return new DependedDependency($neededDependency);
        // Alternatively, if you use the UsesDependencyContainer trait in DependedDependency
        return DependedDependency::withDependencies();
    });
]);
```

Lastly, use the `Container` object within your classes:

```php
use Looqa\Dic\Traits;

class DependentClass {
    use UsesDependencyContainer;

    public function __construct(ISomeDependency $dep1, DependentDependency $dep2) {
        //..some code..
    }
}

$dependentClass = DependentClass::withDependencies();
```

## Container

The container is a singleton instance that stores an array of resolvers, which implement the necessary dependencies.

### Initialization

You can initialize the `Container` with or without initial resolvers. If needed, pass an array of resolvers:

```php
Container::make([
    SomeDependency::class => ClosureResolver::make(ResolutionMode::Singleton)->closure(function () {}),
    //...
]);
Container::make();
```

### Adding Dependencies

You can add a new dependency with or without overwriting. Function `add()` allows you to add or replace dependencies in the container. 

The third parameter of the function `add()` is designed to warn you about dependency replacement. 
This is because if you want to replace some dependency, you may need to close connections, etc. 
The function throws an exception if the dependency already exists and the `$overwrite` parameter is false (which is the default):

```php
Container::instance()->add(SomeDependency::class, ClosureResolver::make(/* Resolution mode */)->closure(/* closure.. */));

// When need to replace some dependency
if (Container::instance()->has(SomeDependency::class)) {
    $dep = Container::instance()->get(SomeDependency::class);
    $dep->closeConnection();
}
// Pass in third parameter true, it makes you sure that previous dependency won't leave any leaks
Container::instance()->add(SomeDependency::class, ClosureResolver::make(/* Resolution mode */)->closure(/* closure.. */)), true);
```

### Getting Instances and Checking Existence

You can get an instance of a dependency or check if a resolver for a certain dependency exists:

```php
// Get an instance
// Throws ResolveNotFoundException if the resolver does not exist
Container::instance()->get(SomeDependency::class);

// Check if the container has a resolver
Container::instance()->has(SomeDependency::class);
```

## Resolvers and Resolution Mode

In the current version, the library implements a single resolver - `ClosureResolver`, which allows you to create a closure to resolve a dependency.

You can implement any other resolver you need by extending the abstract class `Resolver`.

The resolution mode can be either `Singleton` or `Factory`, which defines whether the `Resolver` should store 
the first resolution result or create a new object every time it is queried.

## Using the Construction Trait

The library includes the `UsesDependencyContainer` trait, which allows you to construct objects of client classes 
automatically while injecting dependencies using the `Container` instance.

The `withDependencies()` function resolves dependencies in the class constructor using reflection. You just need to place all the dependencies 
as the first parameters of the `__construct()` function of the dependent class.

For example:

```php
use \Looqa\Dic\Traits\;

class DependencyClientClass {
    use UsesDependencyContainer;
    
    private IDatabase $database;
    private IFileStorage $storage;
    
    public function __construct(IDatabase $database, IFileStorage $storage) {
        $this->database = $database;
        $this->storage = $storage;
    }
}

// When you need DependencyClientClass, just call the trait method, and the dependencies will be automatically resolved.
$client = DependencyClientClass::withDependencies();
```

You can also pass additional parameters needed in the constructor. Just pass them after the dependencies that need to be resolved.

**Warning**: In the current version, the additional parameters must be of built-in types.

```php
// The same DependencyClientClass
public function __construct(IDatabase $database, IFileStorage $storage, string $someString, int $someNumber) {
    $this->database = $database;
    $this->storage = $storage;
    //...
}

$client = DependencyClientClass::withDependencies("some string", 5);
```