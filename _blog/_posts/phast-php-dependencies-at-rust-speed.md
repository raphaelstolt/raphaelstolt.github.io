---
title: 'Phast — PHP dependencies at Rust speed'
category: php, tooling, performance, rust
author: raphaelstolt
date: '2026-11-18 10:50'
---

If you've spent any time working in large PHP codebases or waiting on CI pipelines, you know how much cumulative developer time is lost to dependency resolution and package extraction. 

Enter **[Phast](https://phast.sh/)** — an extremely fast, modern dependency manager for PHP written in Rust.

```
❯ phast install -v
Resolved 119 packages in 0.31s
Locked 119 packages in 0.12s
Installed 119 packages in 1.70s
Autoloaded 119 packages in 0.13s
Finished in 2.26s (119 packages)
```

## Why Phast?

The PHP ecosystem has seen a massive performance leap in recent years through native tooling like Mago. Phast brings that same performance focus to package management:

- **Near-Instant Installs:** Resolves, downloads, and links dependencies in parallel. Cold installs take a couple of seconds instead of minutes; warm reinstalls finish in fractions of a second.
- **Drop-in Compatibility:** Works directly with Packagist, Private Packagist, Satis, Git repos, and local paths. It outputs a standard, optimized `vendor/autoload.php` that works seamlessly with PHP 8.0+.
- **Clean TOML Manifests:** Introduces `phast.toml` and `phast.lock` for human-friendly configuration and first-class editor auto-completion.
- **Effortless Migration:** Run `phast migrate` to import an existing `composer.json` without having to rewire your project.
- **First-Class Monorepos:** Built-in `[workspace]` support resolves multiple packages together into a single shared lock file and `vendor/` tree.
- **Built-in Security & Auditing:** Comes with `phast audit` to check security advisories and guard against supply-chain vulnerabilities with minimum package age checks.

## Getting Started

Installing Phast is a single command:

```bash
curl -fsSL https://phast.sh/install | sh
```

You can verify the setup and import an existing project in seconds:

```bash
# Check version
phast cli version

# Import your existing composer.json
phast migrate

# Install dependencies at lightning speed
phast install
```

## The Net Win

Every second shaved off local dependency updates and CI build times compounds across engineering teams and automated pipelines. Shorter feedback loops mean fewer context switches, faster continuous integration cycles, and a smoother development experience.

Give it a spin at [phast.sh](https://phast.sh/) or check out the [documentation](https://docs.phast.sh/).
