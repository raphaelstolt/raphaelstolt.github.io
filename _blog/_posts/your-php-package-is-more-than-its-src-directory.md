---
title: 'Your PHP package is more than its src/ directory'
category: php, oss, opinion
author: raphaelstolt
date: '2026-11-19 00:48'
---

A PHP package might contain only a few hundred lines of production code, yet maintaining it responsibly requires considerably more work. Tests, static analysis, coding standards, documentation, dependency updates, release automation, semantic versioning, and backward compatibility all contribute to the real cost of a package.

The interesting question is: at what point does the tooling surrounding a PHP package become more complex than the package itself?

## The illusion of a small package

A library with five classes sounds simple. You open the repository, glance at the `src/` directory, and think you've understood the whole thing in minutes. But that directory is only the visible tip.

Behind those five classes sits a test suite that exercises every edge case. There's a static analysis configuration catching type errors before they reach users. A coding style enforcer keeps the codebase readable. A CI pipeline runs on every push. A changelog tracks what changed and why. A release workflow tags versions and publishes artifacts.

The code is small. The surrounding ecosystem is not.

## The invisible infrastructure

Every well-maintained PHP package carries an invisible stack of tooling:

PHPUnit or Pest for tests. PHPStan or Psalm for static analysis. PHP-CS-Fixer, Mago, or Rector for code style and automated refactoring. Composer scripts that wire everything together. GitHub Actions workflows that run the entire suite on multiple PHP versions. Release automation that bumps versions, tags commits, and publishes to Packagist.

None of this is in `src/`. None of it is the "product." But without it, the package is just code sitting in a repository — untested, unchecked, and unmaintained.

I've felt this tension building tools like [php-lean](https://github.com/lean-packages/php-lean) and [composer-peel](https://github.com/lean-packages/composer-peel). The core logic of either tool is only a few hundred lines. The surrounding infrastructure — tests, CI matrices, release workflows, documentation — dwarfs the actual code.

## The dependency paradox

Adding a development dependency can improve quality while simultaneously increasing maintenance obligations.

You add PHPStan to catch type errors. Now you have a configuration file to maintain, baseline files to manage, and a new failure mode in CI. You add Rector to automate refactoring. Now you need to test its rules against your codebase on every PHP version upgrade. You add a GitHub Action for automated releases. Now you're responsible for keeping that action up to date.

Every tool is both a solution and a new thing to maintain. The dependency graph of a package's tooling often grows faster than the dependency graph of the package itself.

## The cost of backward compatibility

Supporting multiple PHP versions and evolving public APIs becomes progressively harder with each release.

A seemingly harmless change to a public API creates a compatibility obligation:

```php
// Version 1.0
public function validate(string $path): bool;

// Version 2.0
public function validate(
    string $path,
    bool $strict = false,
): ValidationResult;
```

The additional optional parameter is relatively harmless for ordinary callers. But changing the return type from `bool` to `ValidationResult` is a breaking change for consumers expecting a boolean. Implementations overriding the method face additional compatibility issues.

The code itself isn't particularly complicated. The consequences of changing it are.

Every public method is a contract. Every supported PHP version is a test matrix row. Every release is a point from which you can't easily take back a decision.

## When automation becomes overhead

Not every package needs an elaborate CI matrix, multiple quality gates, or a sophisticated release process.

I've seen tiny utilities with full PHP version matrices spanning five major versions, three static analysis tools running in parallel, and automated changelog generation. The package does one thing: it trims whitespace from a string. The CI pipeline takes longer to run than the code took to write.

This isn't hypothetical. It's the natural trajectory. You start with a simple tool. It works. Then you add tests because that's good practice. Then you add static analysis because that's good practice. Then you add a CI matrix because that's good practice. Each step is individually reasonable. Together, they create a maintenance burden that outpaces the value of the package.

Working on [config-cartographer](https://github.com/raphaelstolt/config-cartographer) taught me this lesson directly. The tool's purpose is to map configuration structures — a straightforward concept. But the tooling around it — the test fixtures, the validation rules, the CI configuration — became something that itself needed maintaining.

## Finding the sweet spot

The goal isn't to abandon quality. It's to establish a minimum viable maintenance setup that grows with the package.

Start with tests for the core logic. Add static analysis when the codebase reaches a size where manual review becomes unreliable. Introduce a CI matrix when you actually have users on multiple PHP versions. Build release automation when you're making more than one release per month.

Let the tooling follow the package, not lead it.

A small codebase doesn't necessarily mean a small maintenance burden. Every dependency, public method, supported PHP version, and release workflow represents a commitment.

The art of maintaining a PHP package isn't just knowing what to add. It's knowing which commitments are worth making.
