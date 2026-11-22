---
title: "Configuration is code. Why don't we treat it that way?"
category: php, configuration, oss
author: raphaelstolt
date: '2026-11-18 11:59'
---

In modern software development, we treat application source code with intense discipline. We run strict static analysis (PHPStan at Level 9 or Mago), enforce automated formatting, run mutation tests with Infection, and write architectural boundaries to ensure domain layers never leak.

And then there's application configuration.

Configuration controls feature flags, rate limits, database credentials, queue timeouts, and external API integrations. Changing a single configuration value can alter system behavior just as drastically as deploying a new controller or refactoring a domain model. Yet, in most codebases, configuration is treated as an afterthought—a fragile collection of string keys scattered across `.env` files, framework configs, and stray `getenv()` or `$_ENV` calls.

Why do we treat configuration as second-class citizens when it is fundamentally code?

## The Configuration Discoverability Problem

Consider what happens when you onboard to a new project or prepare to refactor an existing service. You open `.env.example` to see what environment variables the application requires.

Immediately, you hit the classic questions:

1. **Where is this variable actually used?** Is `PAYMENT_GATEWAY_TIMEOUT` read directly by an HTTP client, passed through a framework config array, or injected via a dependency injection container?
2. **What breaks if I rename or delete this key?** Is there a fallback value buried three layers deep in a config file, or will the application throw a 500 error in production on an unhandled null?
3. **What is missing from `.env.example`?** How many `getenv('SOME_SECRET_FEATURE')` calls exist in legacy classes that were never documented?

Because configuration spans multiple disjointed layers, it's virtually impossible to build a mental map through simple text search alone:

```
[ .env / .env.example ]
        │
        ▼ (Environment layer)
[ config/services.php ]
        │
        ▼ (Framework / Config layer)
[ app/Services/PaymentClient.php ] (Application layer via getenv / $_ENV)
```

This structural gap creates **configuration drift**: `.env.example` falls out of sync with actual code, unused legacy variables linger forever because nobody is brave enough to delete them, and undocumented variables silently fail when deployed to new environments.

## Static Visibility Without Runtime Execution

If we want to treat configuration like code, we need tools that inspect configuration dependencies with the same rigor as our code linters and static analyzers.

Crucially, this needs to happen **statically**:
- **No runtime execution:** We shouldn't need a running database, booted framework kernel, or populated container just to inspect variable references.
- **Zero secret leakage:** Tooling should trace the *existence and flow* of configuration keys without ever reading or logging sensitive runtime values.
- **Source-level evidence:** Every mapped variable should point directly to the exact file and line number where it's declared or consumed.

## Enter `config-cartographer`

To solve this discovery and drift problem, I built **[`config-cartographer`](https://github.com/raphaelstolt/config-cartographer)** (`stolt/config-cartographer` on Packagist) — a PHP library and CLI tool for discovering, tracing, and visualizing application configuration dependencies.

It statically scans your project's `.env` files, configuration declarations, and PHP source code to build a complete cross-layer dependency graph.

### 1. Discovering Configuration (`scan`)

The `scan` command crawls the codebase and maps all configuration declarations against their real usages:

```bash
php bin/config-cartographer scan
```

It surfaces:
- Environment variables declared in `.env` and `.env.example`.
- Variables defined in framework configuration files (e.g., Laravel `config/*.php` or Symfony packages).
- Direct access points like `getenv()`, `$_ENV`, and framework config helpers.
- **Drift alerts:** variables defined in `.env.example` that are never read, or variables read in code that are missing from `.env.example`.

### 2. Tracing Impact (`trace`)

When you want to know what depends on a specific variable before touching it:

```bash
php bin/config-cartographer trace DB_POOL_TIMEOUT
```

`trace` tracks the variable through every hop—from `.env` into configuration arrays and down to the specific classes consuming it:

```
Variable: DB_POOL_TIMEOUT
├── Declared: .env.example (line 14)
├── Referenced: config/database.php (line 42)
└── Consumed: app/Infrastructure/Database/ConnectionPool.php (line 28)
```

### 3. Visualizing Dependency Graphs (`graph`)

Sometimes you need to see the entire configuration architecture at a glance. `config-cartographer` can export full dependency graphs in JSON or Mermaid format:

```bash
php bin/config-cartographer graph --format=mermaid > config-map.md
```

You can render it directly in GitHub Markdown or your documentation to give developers and AI agents instant visual clarity over how configuration flows through the system.

## Getting Started

You can install `config-cartographer` as a development dependency via Composer:

```bash
composer require --dev stolt/config-cartographer
```

Or run it directly against your project:

```bash
php bin/config-cartographer --help
```

## Treating Configuration with the Rigor It Deserves

When configuration is treated as an untyped, untracked black box, configuration drift and deployment failures are inevitable. By bringing static analysis, dependency tracing, and automated discoverability to our configuration layer, we eliminate guesswork and make our systems more resilient.

Check out [`stolt/config-cartographer` on Packagist](https://packagist.org/packages/stolt/config-cartographer) and [GitHub](https://github.com/raphaelstolt/config-cartographer).
