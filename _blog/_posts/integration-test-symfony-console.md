---
title: 'Two ways to integration-test a symfony/console command with Pest'
category: integration-test, symfony/console, pest
author: raphaelstolt
date: '2026-11-17 14:00'
---

When a PHP CLI application is built with Symfony Console, you can test its commands without starting a separate shell process. Pest provides the test structure: each `it()` closure is a test case, and its `expect()` function makes assertions. Within that structure, you can use Symfony's built-in tester or Zenstruck's `console-test` package.

## The Symfony-native approach: `CommandTester`

`CommandTester` executes a command directly. This complete Pest test constructs the command, passes its argument, then uses Pest expectations to check the status code and captured display:

```php
<?php

use App\Command\GreetCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

it('greets a custom name', function () {
    $command = new GreetCommand();
    $commandTester = new CommandTester($command);

    $statusCode = $commandTester->execute([
        'name' => 'Alice',
    ]);

    expect($statusCode)->toBe(Command::SUCCESS)
        ->and($commandTester->getDisplay())->toContain('Hello, Alice!');
});
```

This style is explicit and uses Symfony's own testing API. Pest handles the test case and expectations, while `CommandTester` handles command input, execution, status, and output. It is a good fit when you want direct access to those results or prefer to keep test dependencies minimal.

## The fluent approach: Zenstruck `TestCommand`

Zenstruck's `TestCommand` wraps command setup, execution, and common assertions in a chain. Here it runs inside a Pest `it()` test; arguments can be given as a console-like string:

```php
<?php

use App\Command\SumCommand;
use Zenstruck\Console\Test\TestCommand;

it('calculates the sum of given numbers', function () {
    TestCommand::for(new SumCommand())
        ->execute('1 2 3 4 5')
        ->assertSuccessful()
        ->assertOutputContains('Sum: 15');
});
```

The fluent chain contains Zenstruck's assertions rather than Pest's `expect()`: the demo also builds arguments and an option with methods, and checks JSON output using `assertOutputContains()`. `assertSuccessful()` makes the expected successful exit status easy to read; the chain can also express other expectations supported by the package.

## Testing the application, not just a command

Both styles can cover more than an isolated command. Symfony's `ApplicationTester` exercises command lookup and application-level behavior, such as the version option. Zenstruck can run a registered command through the application with `TestCommand::from()` inside a Pest test:

```php
<?php

use App\Application;
use Zenstruck\Console\Test\TestCommand;

it('runs the sum command through the full application', function () {
    TestCommand::from(new Application(), 'sum 100 250')
        ->execute()
        ->assertSuccessful()
        ->assertOutputContains('Sum: 350');
});
```

These are still in-process tests using Symfony's testing utilities, not tests that launch `bin/cli-under-test` as a separate operating-system process. A missing required argument is validated as an exception in the demo, rather than asserted as a command failure status.

## Which should you choose?

Use `CommandTester` when you want Symfony's direct, explicit API. Choose `TestCommand` when its readable fluent assertions and convenient input builder make tests easier to scan; it does add a development dependency. For coverage of application wiring or global options, test through the application rather than only constructing a command by itself. The approaches can coexist, as they do here.