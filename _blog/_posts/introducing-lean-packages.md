---
title: 'Introducing lean-packages — Lean distributions across ecosystems'
category: oss, packaging
author: raphaelstolt
date: '2026-11-17 22:00'
---

The [lean-packages](https://github.com/lean-packages) GitHub organization provides tools for creating, validating, and managing lean software package distributions across multiple ecosystems. The idea is simple: a distribution archive should contain only what the end user actually needs to run the software, nothing more.

## The problem

When you publish a package — whether it's a PHP library on Packagist, a Python wheel on PyPI, or a Rust crate on crates.io — the distribution archive often includes far more than the runtime requires. Source code, tests, documentation builds, CI configuration, editor settings, and development-only dependencies all get bundled in. For a library that weighs a few kilobytes, the archive might be tens of megabytes because it carries the entire development environment along for the ride.

This bloat has real consequences: slower downloads, larger caches, longer install times, and unnecessary bandwidth costs multiplied across every consumer of the package.

## The solution

The lean-packages project addresses this with a suite of ecosystem-specific tools, each tailored to the packaging conventions of its language:

- **[php-lean](https://github.com/lean-packages/php-lean)** — A library and CLI for validating and managing package release contents in lean distribution archives.
- **[composer-peel](https://github.com/lean-packages/composer-peel)** — A small development tool to strip development-only metadata from `composer.json` when releasing PHP packages for distribution.
- **[cargo-lean](https://github.com/lean-packages/cargo-lean)** — A Cargo tool for validating and optimizing Rust crate distribution contents.
- **[py-lean](https://github.com/lean-packages/py-lean)** — A Python tool for validating and optimizing package distribution contents.
- **[js-lean](https://github.com/lean-packages/js-lean)** — A CLI tool for validating and optimizing JavaScript package distribution archives.
- **[ruby-lean](https://github.com/lean-packages/ruby-lean)** — A CLI for linting RubyGem distribution contents using native gemspec and RubyGems packaging rules.
- **[zig-lean](https://github.com/lean-packages/zig-lean)** — A CLI for validating and optimizing Zig package contents through `build.zig.zon`.

## Unified CI integration

Rather than requiring separate CI steps for each language, the [action](https://github.com/lean-packages/action) repository provides a single GitHub Action that can lint package distribution contents across all supported ecosystems. One workflow step covers PHP, Ruby, Rust, Python, JavaScript/TypeScript, and Zig.

## Why it matters

Lean distributions are not just about smaller file sizes. They are about shipping the right artifact — one that contains only production-ready code and metadata. Development tooling, test fixtures, and configuration noise have no place in a release archive. The lean-packages tools make it easy to catch these issues early, whether locally or in CI, before they reach your users.

The project is open source and MIT-licensed. Check it out at [lean-packages](https://lean-packages.github.io).
