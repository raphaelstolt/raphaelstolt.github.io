---
title: 'pds/skeleton — A standard filesystem structure for PHP packages'
category: oss, php, standards
author: raphaelstolt
date: '2026-11-18 05:30'
---

The [php-pds/skeleton](https://github.com/php-pds/skeleton) publication defines a standard filesystem skeleton suitable for all PHP packages. It's part of the broader [PHP Package Development Standards](https://php-pds.com) (PDS) initiative, which researches the PHP package ecosystem to recognize and rationalize commonly adopted development practices.

## The problem

PHP packages on Packagist use wildly inconsistent directory and file names. One library puts source code in `lib/`, another in `src/`, a third in `source/`. Tests live in `test/`, `tests/`, `test-suite/`, or `spec/`. There's no convention, so tooling that wants to work with any package — linters, validators, CI tools — has to guess or configure the layout for each one.

## The fix

The PDS initiative examined thousands of existing PHP packages to find what they already have in common. The research found clear patterns:

- **90%** of packages use a `README` file
- **67%** use a directory for PHP source code
- **58%** include a licensing file
- **38%** have a directory for test files

From this data, the `pds/skeleton` standard defines a set of recommended names:

- **`src/`** — PHP source code files
- **`tests/`** — Test files
- **`docs/`** — Documentation files
- **`bin/`** — Command-line executable files
- **`public/`** — Files served via a web server
- **`resources/`** — Other resource files
- **`config/`** — Configuration files
- **`README`** — Package information file
- **`LICENSE`** — Licensing file

The standard also provides command-line tools for `validating` or `generating` PDS-conformant packages.

## Why it matters

A common filesystem structure isn't just about aesthetics. It enables tooling that works across the entire ecosystem without per-package configuration. Validators, linters, scaffolding tools, and CI pipelines can all rely on a predictable layout — making the PHP ecosystem more cohesive and developer-friendly.

Check it out at [github.com/php-pds/skeleton](https://github.com/php-pds/skeleton).
