---
title: 'Your CLI deserves better tests'
category: php, symfony/console, phpunit, zenstruck
author: raphaelstolt
date: '2026-11-18 12:10'
---

CLI tools are the backbone of developer tooling, deployment automation, batch data processing, and CI/CD pipelines. Yet, in many PHP codebases, CLI commands receive the worst testing treatment of any layer in the stack.

You’ve probably seen the extremes:
- **The "just run it" approach:** No tests at all. You test your command by running `php bin/console app:do-something` manually against your local database and checking if it looks right.
- **The subshell nightmare:** Running `exec()` or `shell_exec()` in PHPUnit, spawning a new OS process for every assertion, making the test suite agonizingly slow and brittle.
- **The mock disaster:** Mocking `InputInterface` and `OutputInterface` line-by-line in a unit test, asserting that `writeln()` was called 14 times with specific ANSI escape sequences.

Testing command-line applications doesn't have to be painful. Over years of building and maintaining PHP CLI packages and tools, distinct testing paradigms—from Symfony Console's native testers to PHPSpec, PHPUnit/Pest, and Zenstruck—have taught us how to design and test CLI commands effectively.

Here are the practical lessons.

---

## 1. PHPSpec: Commands Are Just HTTP Controllers in Disguise

If you ever used [PHPSpec](https://phpspec.net/) or embraced SpecBDD, you know it forces you to think about object design through behavior and interfaces. 

When you try to test a 200-line Symfony Console command with PHPSpec, the friction is immediate. Mocking `InputInterface`, `OutputInterface`, helpers, and styles is so cumbersome that it serves as an architectural alarm bell: **your command has too many responsibilities**.

### The Lesson
A CLI command is an input/output adapter, identical in purpose to an HTTP controller. Its only jobs are:
1. Parse arguments and options.
2. Delegate the actual work to a dedicated service or domain action.
3. Map the result to an exit code and format the output.

```
┌──────────────────────────────────────────────┐
│             Symfony Console Layer            │
│  [ Input Definition ] ──> [ Exit Status/UI ] │
└───────────────────────┬──────────────────────┘
                        │ (Delegates)
                        ▼
┌──────────────────────────────────────────────┐
│                 Domain Action                │
│       ProcessOrder / AnalyzeRepository       │
└──────────────────────────────────────────────┘
```

When you extract core logic into pure domain services, unit testing or specifying business rules with PHPSpec or PHPUnit becomes effortless. Your CLI tests can then focus exclusively on input parsing, command output, and exit status.

---

## 2. Symfony Console: In-Process Execution with `CommandTester`

Symfony Console provides built-in testing utilities: `CommandTester` and `ApplicationTester`. They execute commands entirely in-process, without spawning subshells or opening separate processes.

```php
use App\Command\DeployCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

public function testDeployCommand(): void
{
    $commandTester = new CommandTester(new DeployCommand($mockService));

    $statusCode = $commandTester->execute([
        'environment' => 'staging',
        '--dry-run' => true,
    ]);

    $this->assertSame(Command::SUCCESS, $statusCode);
    $this->assertStringContainsString('Dry-run deploy finished successfully.', $commandTester->getDisplay());
}
```

### The Lesson
- **Never use `shell_exec()` for standard test suites.** `CommandTester` captures the output buffer and returns exit codes in memory in milliseconds.
- **Always assert explicit status codes.** Rely on `Command::SUCCESS` (0), `Command::FAILURE` (1), and `Command::INVALID` (2) rather than arbitrary magic numbers.
- **Test the full application wiring with `ApplicationTester`.** Don't just test commands in isolation; test through `ApplicationTester` to ensure command registration, aliases, and global options (`--no-interaction`, `--quiet`, `--env`) behave as expected.

---

## 3. PHPUnit & Pest: Testing Error Paths and Exit Contracts

CLI tools live in automated environments where exit codes dictate whether a deployment passes or fails. A common mistake in PHPUnit tests is verifying only the happy path output string while ignoring error handling and invalid arguments.

```php
it('fails gracefully when the configuration file is missing', function () {
    $tester = new CommandTester(new ValidateConfigCommand());

    $exitCode = $tester->execute([
        '--config' => 'non-existent.json',
    ]);

    expect($exitCode)->toBe(Command::FAILURE);
    expect($tester->getDisplay())->toContain('Error: Configuration file not found');
});
```

### The Lesson
- **Test exit codes as contracts:** Scripts and CI runners check `$?`. If your command fails but returns `0`, you break downstream automation.
- **Test missing and invalid options:** Verify how the command reacts when required arguments are omitted or when mutually exclusive options are passed together.
- **Test interactive prompts non-interactively:** Use `setInputs()` on `CommandTester` to simulate user confirmation dialogs and choices deterministically.

---

## 4. Zenstruck `console-test`: Modern, Fluent Ergonomics

While Symfony's `CommandTester` is rock solid, it can feel verbose when setting up input arrays and manually asserting substrings. 

Enter [Zenstruck Console Test](https://github.com/zenstruck/console-test) (`zenstruck/console-test`), which brings fluent testing ergonomics to Symfony Console:

```php
use App\Command\GenerateReportCommand;
use Zenstruck\Console\Test\TestCommand;

public function testGenerateReport(): void
{
    TestCommand::for(new GenerateReportCommand())
        ->execute('sales --format=json --period=monthly')
        ->assertSuccessful()
        ->assertOutputContains('Generating report...')
        ->assertJson();
}
```

### The Ergonomics Leap

1. **Console-Like Invocation:** Instead of mapping verbose associative arrays, you pass arguments and options exactly as you type them in the terminal (`'sales --format=json'`).
2. **Chainable Assertions:** Built-in helpers like `assertSuccessful()`, `assertFailure()`, `assertStatusCode()`, `assertOutputContains()`, and `assertOutputNotContains()` make tests readable at a glance.
3. **Interactive Prompt Testing:** Testing questions, hidden passwords, and multi-select choices becomes clean and expressive:

```php
TestCommand::for(new UserCreateCommand())
    ->splitOutput() // Separates stdout from stderr
    ->execute()
    ->answers(['Raphael', 'secret-password', 'admin'])
    ->assertSuccessful()
    ->assertOutputContains('User created successfully');
```

4. **Testing Application Kernels:** You can invoke commands registered across the full application kernel without manual instantiation:

```php
TestCommand::from($application, 'app:generate-report monthly')
    ->execute()
    ->assertSuccessful();
```

---

## Summary: A Blueprint for Solid CLI Tests

1. **Keep commands thin:** Treat CLI commands as UI adapters (a lesson reinforced by PHPSpec). Move business logic into domain services.
2. **Execute in-process:** Use `CommandTester` or Zenstruck's `TestCommand` to run commands in memory without subshell overhead.
3. **Assert exit codes first:** Treat exit codes (`Command::SUCCESS`, `Command::FAILURE`, `Command::INVALID`) as critical API contracts.
4. **Test both output streams:** Distinguish standard output from error output (`stderr`) to ensure machine-readable outputs remain clean.
5. **Adopt fluent helpers:** Leverage packages like `zenstruck/console-test` to write expressive, maintainable CLI test suites.

Your CLI tools automate critical workflows—give them the test coverage and ergonomics they deserve.
