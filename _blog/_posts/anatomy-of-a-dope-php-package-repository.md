---
title: 'Anatomy of a dope PHP package repository'
category: open-source, php, packaging, ai
author: raphaelstolt
date: '2026-11-18 07:45'
---

A decade ago, back in [September 2016](https://raphaelstolt.blogspot.com/2016/09/anatomy-of-dope-php-package-repository.html), I wrote about the characteristics and anatomy of a dope PHP package repository while contributing to [Construct](https://github.com/jonathantorres/construct) by Jonathan Torres and drawing inspiration from Jonathan Reinink's PHP Package Checklist.

Fast-forward to **2026**. The PHP ecosystem has matured dramatically. We are building with PHP 8.3, 8.4, and 8.5, leveraging first-class enums, readonly classes, fiber-based concurrency, property hooks, asymmetric visibility, pattern matching, and comprehensive static type systems.

Crucially, we have entered the **AI era**. Today, package repositories are no longer consumed solely by human developers; they are continuously indexed, analyzed, tested, and refactored by autonomous AI coding agents, language models, and automated CI bots.

So, what constitutes a truly **dope PHP package repository in 2026**?

---

## Redefining the Dope Package Repository

In 2016, we adapted Michael Feathers' classic clean code quote:

> *A dope PHP package repository is one that is created and maintained by someone who cares.*

In 2026, we extend this definition for the AI and automation age:

> *A dope PHP package repository is one engineered with such structural clarity, strict typing, and machine-readable context that both human contributors and AI agents can reason about, verify, and evolve it effortlessly.*

```text
               ▲
              / \
             /   \
            /  3  \          3. AI & Automation Tooling
           /-------\            (Agent Context, Rector, Infection, Release Please)
          /    2    \        2. Strict Quality Gates & Continuous Inspection
         /-----------\          (PHPStan Max, Mago / Pint, Pest Architecture Tests)
        /      1      \      1. Source, Contracts, Documentation & Packaging
       /---------------\        (Strict PHP 8 Types, Lean Dists, Docs, PDS Skeleton)
```

---

## The 2026 Repository Artifact Skeleton

A modern, comprehensive PHP package repository layout for 2026 following [pds/skeleton](https://github.com/php-pds/skeleton) conventions looks like this:

```text
├── <package-name>
│   ├── .editorconfig
│   ├── .gitattributes
│   ├── .gitignore
│   ├── .github/
│   │   ├── copilot-instructions.md       # AI agent & LLM context
│   │   ├── dependabot.yml                # Dependency maintenance
│   │   ├── ISSUE_TEMPLATE/
│   │   │   ├── bug_report.yml
│   │   │   └── feature_request.yml
│   │   ├── PULL_REQUEST_TEMPLATE.md
│   │   └── workflows/
│   │       ├── tests.yml                 # Matrix testing & path filtering
│   │       ├── lint.yml                  # Pint / PHP-CS-Fixer & PHPStan
│   │       ├── mutation.yml              # Infection testing
│   │       ├── security.yml              # Semgrep / CodeQL analysis
│   │       └── release.yml               # Automated SemVer & changelog
│   ├── CHANGELOG.md
│   ├── CODE_OF_CONDUCT.md
│   ├── CONTRIBUTING.md
│   ├── LICENSE.md
│   ├── README.md
│   ├── SECURITY.md
│   ├── composer.json
│   ├── composer.lock                     # Committed for apps, gitignored for libraries
│   ├── mago.toml                         # Rust-based toolchain (linter, formatter, analysis)
│   ├── phpstan.neon.dist                 # Strict static analysis config (Level 9 / Max)
│   ├── phpunit.xml.dist                  # Or Pest.php
│   ├── .php-cs-fixer.dist.php            # Or pint.json (PER Coding Style 2.0)
│   ├── rector.php                        # Automated AST refactoring & modernization
│   ├── infection.json.dist               # Mutation testing configuration
│   ├── bin/                              # CLI binaries (if applicable)
│   ├── src/                              # Typed PHP source code
│   ├── tests/
│   │   ├── Unit/
│   │   ├── Feature/
│   │   └── ArchitectureTest.php          # Architectural boundary assertions
│   └── docs/                             # Dedicated documentation
```

Let's break down the critical traits that elevate a PHP repository from average to dope in 2026.

---

## 1. Agent & AI Readiness: The 2026 Game Changer

In 2026, software engineers regularly use AI coding assistants and autonomous agent workflows. An AI agent is only as good as the context and guardrails you provide.

### AI Agent Guidelines (`.github/copilot-instructions.md`, `CLAUDE.md`, or `.cursorrules`)
High-performing repositories provide explicit instructions for AI models right in the repository root or `.github/` directory. These files declare:
- The target PHP version and forbidden deprecated idioms.
- Architectural boundaries (e.g., "Domain models must not import infrastructure services").
- Preferred libraries and testing frameworks.
- Commands to run for validation.

```markdown
# Repository Guidelines for AI Agents

- **Language Target:** PHP 8.4+ with `declare(strict_types=1);` in every file.
- **Toolchain & Style:** Use Mago or follow PER Coding Style 2.0 (`mago format` or `composer lint:fix`).
- **Static Analysis:** PHPStan Level 9 compliance (`mago lint` / `phpstan analyse`). Explicitly document generics (`@template`, `@param list<string>`).
- **Testing:** Pest v3. Write comprehensive unit tests for every new feature or bug fix.
- **Immutability:** Value objects should be `readonly` and finalized by default.
```

### Self-Describing Composer Scripts
AI tools and human contributors should be able to run common tasks through standardized Composer scripts without guessing CLI flags:

```json
{
    "scripts": {
        "test": "pest --parallel",
        "test:coverage": "pest --coverage --min=90",
        "test:mutation": "infection --threads=4 --min-msi=80",
        "lint": "mago lint",
        "format": "mago format",
        "analyse": "phpstan analyse --memory-limit=1G",
        "refactor": "rector process --dry-run",
        "refactor:fix": "rector process",
        "check": [
            "@lint",
            "@analyse",
            "@test"
        ]
    }
}
```

---

## 2. Strong Types, Static Analysis, and Modern Rust Tooling

In 2016, we relied heavily on docblocks and basic unit tests. In 2026, type safety, static verification, and ultra-fast developer loops are non-negotiable.

### The Rise of Rust-Powered Tooling: [Mago](https://mago.dev/)
One of the most consequential advancements in modern PHP development is the emergence of native, ultra-fast toolchains like **Mago**. Written in Rust, Mago delivers sub-second linting, formatting, and static analysis across large codebases. 

By unifying multiple disparate utilities into a single, cohesive, zero-config binary, Mago dramatically shrinks the feedback loop for both human engineers and AI coding agents. In CI workflows and agent development loops where every second of latency compounds, Mago provides near-instantaneous validation.

### Comprehensive PHP 8 Type System
- Use native parameter, return, and property types everywhere.
- Leverage `readonly` classes, typed constants, and intersection/union types.
- Strict scalar types enabled on every file: `declare(strict_types=1);`.

### PHPStan at Level 8, 9, or Max
A dope repository pairs Mago's blazingly fast checks with [PHPStan](https://phpstan.org/) (or Psalm) on its strictest levels. Where native types cannot express advanced constraints (such as generic collections or non-empty strings), detailed PHPDoc annotations provide unambiguous contracts:

```php
/**
 * @template TValue of object
 * @param class-string<TValue> $targetClass
 * @return list<TValue>
 */
public function resolveInstances(string $targetClass): array
{
    // ...
}
```

### Automated AST Refactoring with Rector
[Rector](https://getrector.com/) keeps your codebase continuously modernized, automatically upgrading legacy constructs when new PHP versions land and preventing anti-patterns before they get merged.

---

## 3. Beyond Code Coverage: Mutation and Architecture Testing

High line coverage does not guarantee high test quality. In 2026, robust packages rely on deeper verification layers:

1. **Mutation Testing with [Infection PHP](https://infection.github.io/):**
   Infection mutates your source code (e.g., flipping boolean operators, altering return values) and runs your test suite against each mutant. If your tests still pass when logic is inverted, you have a blind spot. A dope repository maintains a high Mutation Score Indicator (MSI).

2. **Architecture Testing with Pest:**
   Ensure design consistency automatically by writing architecture tests:

```php
arch('strict and clean codebase')
    ->expect('App')
    ->toUseStrictTypes()
    ->classes->toBeFinal()
    ->not->toUse(['dd', 'dump', 'ray', 'var_dump']);
```

---

## 4. Lean CI/CD Pipelines: Solving the 2016 Build Wish

Back in 2016, Travis CI lacked selective trigger mechanisms, and we lamented how documentation fixes triggered expensive multi-version test matrices.

In 2026, GitHub Actions completely solves this:

### Path Filtering
Never waste energy and CI runners on pure documentation edits:

```yaml
name: Test Suite

on:
  push:
    branches: [main]
    paths:
      - 'src/**'
      - 'tests/**'
      - 'composer.*'
      - 'phpunit.xml.dist'
  pull_request:
    paths:
      - 'src/**'
      - 'tests/**'
      - 'composer.*'
      - 'phpunit.xml.dist'
```

### Matrix Builds and Concurrency
- Matrix test across supported PHP versions (e.g., PHP 8.2, 8.3, 8.4) with `--prefer-lowest` and `--prefer-stable` dependency flags.
- Use `concurrency` cancellation to terminate obsolete workflow runs when new commits are pushed to a pull request:

```yaml
concurrency:
  group: ${{ github.workflow }}-${{ github.ref }}
  cancel-in-progress: true
```

---

## 5. Lean Release Archives (`.gitattributes`)

The core philosophy of **lean packages** remains just as vital in 2026 as it was in 2016: **a distribution archive should contain only what the end-user requires at runtime**.

When Composer installs a package via `--prefer-dist`, Git creates a release archive based on your `.gitattributes`. Excluding test suites, development tooling, CI workflows, and documentation saves bandwidth, storage, and build time across thousands of downstream installations.

A modern `.gitattributes` file:

```gitattributes
* text=auto eol=lf

# Ignore development configs & workflows
.editorconfig export-ignore
.gitattributes export-ignore
.github/ export-ignore
.gitignore export-ignore
.php-cs-fixer.dist.php export-ignore
infection.json.dist export-ignore
mago.toml export-ignore
phpstan.neon.dist export-ignore
phpunit.xml.dist export-ignore
rector.php export-ignore

# Ignore test suite and documentation
tests/ export-ignore
docs/ export-ignore

# Ignore internal scripts and tooling
bin/dev-tool export-ignore
```

Tooling like [php-lean](https://github.com/lean-packages/php-lean) and [composer-peel](https://github.com/lean-packages/composer-peel) from the [lean-packages](https://github.com/lean-packages) initiative lets you automatically validate and strip development-only metadata on release.

---

## 6. Automated SemVer & Release Management

Gone are the days of manually bumping version constants in PHP binaries and tagging releases by hand.

In 2026, dope packages adopt **Conventional Commits** (`feat:`, `fix:`, `chore:`, `perf:`) combined with automated release tools like [Release Please](https://github.com/googleapis/release-please) or GitHub Release Actions:
- PR merges automatically generate and maintain `CHANGELOG.md`.
- Releases are tagged in alignment with SemVer.
- Composer 2 natively resolves runtime version metadata via `\Composer\InstalledVersions` (`composer-runtime-api`), eliminating the need for hardcoded version constants in source files.

---

## 7. Avoid Badge Posing

Badges are great for high-signal metadata; they are terrible as vanity wallpaper.

**Keep:**
- Latest Stable Version (Packagist)
- CI Build & Test Status (GitHub Actions)
- Required PHP Version (`>= 8.2`)
- Software License (e.g., MIT)

**Avoid:**
- Heavy download count badges (adds external requests and little technical value)
- Total lines of code or complex multi-badge walls

---

## Why Quality and Dopeness Matter in 2026

In 2016, investing in repository quality earned you respect among peer developers and made open-source onboarding smoother.

In 2026, it gives you a **superpower**:
- **Autonomous Velocity:** When your code is strictly typed, statically analyzed at max levels, and accompanied by machine-readable instructions, AI agents can reliably generate bug fixes and features without introducing regressions.
- **Low Maintenance Overhead:** Automated dependency updates, release automation, and mutation tests catch breaking changes long before users report them.
- **Ecosystem Respect:** Consumers trust packages that demonstrate discipline, fast runtime footprints, and rock-solid quality guarantees.

---

## Le Fini

Tools, PHP versions, and development paradigms evolve, but the underlying principle remains unchanged: **pride in craftsmanship**.

Whether you're crafting a micro-library or an enterprise foundation, building a dope package repository is the best gift you can give to your users, your human contributors, and your AI co-pilots.

Happy packaging!
