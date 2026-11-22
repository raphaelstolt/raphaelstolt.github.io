---
title: 'Building PHP Packages with AI: Prompts, Tests, and Quality Gates'
category: php, ai, oss
author: raphaelstolt
date: '2026-11-19 01:00'
---

AI coding assistants can generate a PHP class in seconds. But building a maintainable, distributable package — one with tests, static analysis, CI, and proper Composer metadata — requires more than a prompt. It requires discipline.

This article documents a workflow for using AI as a development partner while keeping software engineering standards intact. It's not about letting AI write code for you. It's about using AI to accelerate the parts of package development that are tedious, while you focus on architecture, quality, and responsibility.

## Why build a PHP package with AI?

The PHP ecosystem has excellent tooling: Composer for dependencies, PHPUnit and PHPSpec for testing, PHPStan for static analysis, PHP-CS-Fixer for code style, and GitHub Actions for CI. But setting all of this up for a new package is repetitive. You've done it before. You'll do it again.

AI excels at repetitive structure. It can scaffold a package skeleton, generate test boilerplate, write documentation, and draft CI workflows. What it can't do is judge whether the architecture is sound, whether the public API is coherent, or whether the tests actually cover the right edge cases.

The goal is to let AI handle the scaffolding and boilerplate while you retain control over design decisions and quality gates. This approach works for packages of any size, from small utilities to larger libraries.

I've used this workflow for packages like [php-lean](https://github.com/lean-packages/php-lean), [composer-peel](https://github.com/lean-packages/composer-peel), and [config-cartographer](https://github.com/raphaelstolt/config-cartographer). The results are packages that meet the same quality standards as ones built entirely by hand — but faster.

## Start with constraints

Before writing a prompt, define the constraints. AI needs boundaries; without them, it will generate the most generic solution it can find, which is rarely the right one.

Define the scope in concrete terms. What problem does the package solve? What's the minimal public API? Which PHP versions must it support? What dependencies are acceptable?

For example, when building a package to analyze Composer lock files, the constraints might be:

- PHP 8.2+ only
- No runtime dependencies beyond Composer's autoloader
- Single public class with a static entry point
- Must parse `composer.lock` JSON and return structured data
- Tests must cover malformed JSON, missing fields, and edge cases

These constraints become the foundation for every prompt. Instead of asking AI to "build a Composer lock file analyzer," you give it a specification with boundaries. The output is more focused, more correct, and easier to review.

## Give AI context

The quality of AI-generated code is directly proportional to the context you provide. A bare prompt produces generic code. A prompt with your package template, coding standards, and existing patterns produces code that fits your ecosystem.

I use two artifacts to give AI consistent context: a package template and an `AGENTS.md` file.

The [php-package-template](https://github.com/raphaelstolt/php-package-template) provides a starting point with the directory structure, Composer configuration, CI workflows, and tooling already in place. When AI generates code against this template, it doesn't need to invent the scaffolding — it fills in the logic.

The `AGENTS.md` file lives in the repository root and documents the package's conventions: naming patterns, test structure, coding style rules, and the quality gates that must pass. It's a single source of truth that AI can reference instead of guessing at conventions.

```markdown
# AGENTS.md

## Package Conventions
- Source code in `src/`, tests in `tests/`
- PSR-12 coding style, enforced by Mago
- PHPStan level 8, no baselines
- PHPUnit for unit tests, PHPSpec for behavior specs
- CI runs on PHP 8.2, 8.3, 8.4

## Quality Gates
- All tests must pass
- PHPStan must report zero errors
- Code style must be clean
- Release manifest is clean (verified by composer-peel)
```

With this context, AI doesn't generate code that violates your standards. It generates code that already conforms to them, reducing the review cycle from "rewrite this" to "approve this."

## Design before implementation

AI is eager to write code. Resist the urge to let it start implementing before you've finished designing.

The most common mistake when using AI for package development is skipping the design phase. You give it a vague description, it generates a class, and you realize too late that the architecture doesn't match what you intended. Fixing architecture after code exists is expensive — especially when AI has already written tests and documentation around the wrong design.

Spend time on the design first. Define the class hierarchy, the interfaces, the data flow. Write the public API in pseudocode or as method signatures. Only then hand the design to AI for implementation.

For a package that validates package lean-ness, the design might look like:

```php
interface LeanValidator
{
    public function validate(Directory $packageRoot): ValidationResult;
}

class ValidationResult
{
    public function __construct(
        public readonly bool $isValid,
        public readonly array $issues,
    ) {}
}
```

With this design, AI generates the implementation against a fixed contract. You review the implementation against the design, not the other way around.

## Tests are non-negotiable

AI-generated code must be tested with the same rigor as hand-written code. In practice, AI-generated code often needs more testing, not less, because it can introduce subtle bugs that look correct at first glance.

The workflow is simple: ask AI to generate tests alongside the implementation, then run them yourself. Don't trust the test output AI claims to produce. Run the actual commands.

```bash
composer test
composer phpstan
composer cs-fixer
```

If any of these fail, the AI's output needs revision. This isn't a reflection on AI's capabilities — it's a reflection on the fact that AI generates code probabilistically, not deterministically. Sometimes it gets it right on the first try. Often it doesn't.

For packages like [composer-peel](https://github.com/lean-packages/composer-peel), which strips development-only metadata from `composer.json` before release, the tests are critical. The package modifies real `composer.json` files, removing sections like `require-dev`, `scripts`, and `autoload-dev`. If the logic incorrectly removes a runtime section or fails to strip a development one, the released package will be broken or bloated. AI might generate transformation code that works for a simple `composer.json` but fails on edge cases like custom sections, nested scripts, or conditional autoload rules.

The tests catch these edge cases. Static analysis catches type errors. Code style enforcement catches formatting inconsistencies. Together, they form quality gates that AI-generated code must pass before it's merged.

## Prepare for distribution

A PHP package isn't complete until it's ready for Packagist. This means proper Composer metadata, clean release contents, and a CI pipeline that builds and tests on every push.

AI can draft the `composer.json`, but you need to verify it. Check the `name`, `description`, `keywords`, `license`, `autoload` configuration, and `require` constraints. AI often generates overly broad version constraints or includes development dependencies as runtime dependencies.

The release contents should be clean. No test files, no CI configuration, no documentation source files — only the production code and the Composer metadata. Use Composer's `archive` command or a GitHub Actions workflow to create clean release artifacts.

A typical CI workflow for a PHP package looks like:

```yaml
name: CI
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    strategy:
      matrix:
        php: ['8.2', '8.3', '8.4']
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php }}
      - run: composer install --no-interaction
      - run: composer test
      - run: composer phpstan
      - run: composer cs-fixer -- --dry-run
```

This workflow ensures every push is tested against multiple PHP versions, static analysis passes, and code style is clean. AI can generate this workflow, but you should review it to ensure it matches your package's requirements.

## What AI gets wrong

AI is not infallible. It makes mistakes, introduces unnecessary complexity, and generates code with false confidence. Recognizing these patterns is essential for maintaining quality.

**Unnecessary abstraction.** AI loves interfaces, factories, and strategy patterns. It will generate an interface for a class that will never have more than one implementation. It will create a factory for a class with a simple constructor. This isn't wrong in principle — it's wrong in context. A small package doesn't need the architectural overhead of a large framework.

**Incorrect assumptions.** AI may assume a method exists on a class when it doesn't. It may use a PHP feature that's not available in your minimum PHP version. It may reference a Composer package that doesn't exist or has a different API than expected. These mistakes are caught by tests and static analysis, which is why those quality gates are non-negotiable.

**Over-engineering.** Given a simple task, AI will often generate a complex solution. Ask it to parse a JSON file, and it might generate a streaming parser with exception hierarchies and retry logic. The package needs to read a file and decode JSON. Nothing more.

**False confidence.** AI generates code that looks correct but isn't. It might write a test that passes but doesn't actually test the intended behavior. It might write a type hint that's technically valid but semantically wrong. Review the code, not just the output.

I've seen all of these in practice. When building packages with AI assistance, the review process is not optional — it's the primary quality gate. AI generates the first draft. You produce the final product.

## Lessons learned

After building multiple PHP packages with AI assistance, several patterns have emerged.

**Delegate the boilerplate.** Let AI generate test scaffolding, CI workflows, documentation, and repetitive code. These are mechanical tasks where AI is fast and reliable.

**Review the architecture.** Never accept AI's architectural decisions without scrutiny. If it introduces an interface, ask why. If it creates a factory, ask what problem it solves. If it adds a dependency, ask whether it's necessary.

**Own the quality gates.** Tests, static analysis, and code style enforcement are your responsibility. AI can generate the configuration, but you must run the tools, interpret the results, and fix the issues.

**Keep prompts specific.** Vague prompts produce vague code. Specific prompts with constraints, examples, and context produce code that's closer to what you need. The time spent crafting a good prompt is recovered in reduced review time.

**Use a template.** Starting from a package template with established conventions means AI generates code that fits your ecosystem from the first prompt. No reformatting, no restructuring, no convention violations.

The [php-package-ai-skill](https://github.com/raphaelstolt/php-package-ai-skill) and [php-package-template](https://github.com/raphaelstolt/php-package-template) are designed to make this workflow reproducible. The template provides the structure. The skill provides the prompts, conventions, and quality gates. Together, they turn AI from a code generator into a development partner.

AI doesn't replace the software engineer. It amplifies the parts of the job that are repetitive and tedious, freeing you to focus on the parts that matter: architecture, quality, and the judgment that no tool can replicate.

The packages built with this workflow are no different from packages built by hand. They have the same tests, the same static analysis, the same CI pipelines, and the same quality standards. The difference is speed: what used to take a day of scaffolding and configuration now takes an hour of focused design and review.

That's not magic. It's discipline, applied with better tools.