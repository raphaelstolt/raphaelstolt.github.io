---
title: 'Mago — An oxidized PHP toolchain'
category: php, tooling, performance, rust
author: raphaelstolt
date: '2026-11-18 11:00'
---

For years, a robust PHP toolchain meant chaining together several standalone tools: PHPStan or Psalm for static typing, PHP-CS-Fixer or Laravel Pint for code formatting, PHP_CodeSniffer for linting, and Deptrac for architectural boundaries. Each tool had its own configuration, its own cache, and—most painfully—its own PHP-based parser traversing your files independently.

Enter **[Mago](https://mago.carthage.software/latest/en/)** by Carthage Software — a lightning-fast static analyzer, linter, formatter, and architectural guard for PHP, written in Rust.

```
❯ mago check
Analyzing 1,420 files...
✨ No errors found in 0.18s
```

## Three Tools (and More), One Binary

Rather than running multiple heavy tools in sequence, Mago unifies the essential code quality tools into a single compiled binary sharing one blazing-fast Rust parser and execution pipeline:

- **Static Analysis (`mago analyze`):** Catches type errors, dead code, impossible branches, and subtle bugs before runtime. It understands generics, conditional types, Psalm/PHPStan annotations, and type narrowing.
- **Opinionated Linting (`mago lint`):** Flags quality issues, suspicious logic, and stylistic anti-patterns with customizable rules and automated fixes (`mago lint --fix`).
- **Code Formatter (`mago format`):** Automatically formats your PHP codebase at native speed, adhering to modern standards without the overhead of PHP runtime interpreters.
- **Architectural Guard (`mago guard`):** Enforces module and namespace boundaries right inside your primary toolchain so domain layers don't leak into each other.
- **CST / AST Insights (`mago ast`):** Inspect Concrete and Abstract Syntax Trees for deep debugging and tooling extensions.

## Getting Started

You can install Mago with a single shell command:

```bash
curl -fsSL https://mago.carthage.software/install | sh
```

Or initialize it in an existing project:

```bash
# Initialize mago.toml from your composer.json
mago init

# Run static analysis and linting
mago lint
mago analyze

# Format your files
mago format
```

## Why It Matters

Whether you're developing locally with hot feedback loops, running GitHub Actions on pull requests, or letting AI coding agents iterate through test-refactor cycles, speed is developer happiness. When linting and type analysis drop from dozens of seconds to sub-second runs, the whole workflow feels completely frictionless.

Check out the official documentation and give it a try at [mago.carthage.software](https://mago.carthage.software/latest/en/).
