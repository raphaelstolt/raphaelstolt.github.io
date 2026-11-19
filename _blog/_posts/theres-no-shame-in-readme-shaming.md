---
title: "There's no shame in README.md shaming"
category: open-source, opinion, documentation
author: raphaelstolt
date: '2026-11-19 02:00'
---

*Your README is the first thing people see. Treat it like it matters — because it does.*

You've built a solid PHP package. The code is clean, the tests pass, the static analysis is green. But your `README.md` is a single line: "A PHP package for doing things."

That's not a README. That's a placeholder with delusions of grandeur.

## The README is your package's storefront

Most developers never read your source code. They read your README, decide whether your package is worth their time, and move on. If the README is vague, outdated, or missing, they assume the package is too.

This isn't unfair. The README is the only signal most people have. If it's weak, they have no reason to look deeper — even if the code underneath is excellent.

I've been guilty of this. I've pushed packages where the README was an afterthought, written in a hurry after the code was done. The result? Packages that are technically sound but practically invisible because nobody could tell what they did or how to use them.

## What a good README actually does

A good README answers three questions in under ten seconds:

1. **What does this package do?** — A clear, specific description that doesn't require reading between the lines.
2. **How do I install it?** — A Composer command that actually works.
3. **How do I use it?** — A code example that copies and pastes into a working script.

Everything else — architecture decisions, design philosophy, contribution guidelines — is bonus. Get those three things right first.

And don't overlook AI: a well-written README is crucial for AI assistants to understand your project. When using a README-first approach, it serves as the primary prompt input, giving AI the context it needs to help you effectively from the very first interaction.

## The shame part

Here's where the `shaming` comes in.

There's no shame in having a bad README. Every package starts with one. The shame is in leaving it bad.

I've audited repositories where the code is production-quality but the README hasn't been updated since version 0.1. The API changed three times. The installation instructions reference a class that no longer exists. The examples use syntax that was deprecated two versions ago.

The code deserves better. The users deserve better.

## A practical checklist

When I review a package's README, I look for:

- **A minimal badge row.** Build status, PHP version support, Packagist version, license. These signal that the package is actively maintained without saying a word. That said, my own preference has evolved over time: I've moved towards badge-less READMEs. The badges were useful early on, but once a package is stable, they add visual noise without much value. A clean README without badges often reads better and ages more gracefully.
- **A one-sentence description.** Not a paragraph. One sentence that tells me what the package does and who it's for.
- **Installation instructions.** `composer require vendor/package` — simple, direct, copy-pasteable.
- **A usage example.** Real code that demonstrates the primary use case. Not pseudocode. Not "see the docs." Actual code in the README.
- **A link to full documentation.** For packages complex enough to need it, the README points to `docs/` or a dedicated documentation site.

If a README has all five, I trust the package is maintained. If it's missing more than two, I'm already looking for alternatives.

## Writing the README first

The best READMEs aren't written after the code. They're written before it.

When you draft the README first, you're forced to think about the public API, the installation flow, and the primary use case before you've written a single line of implementation. It's a design exercise disguised as documentation.

I use this approach with the [php-package-template](https://github.com/raphaelstolt/php-package-template). The template includes a README skeleton that prompts you to fill in the description, installation, and usage sections before you start coding. It sounds backwards, but it keeps the user experience front of mind from day one.

## The README follows the code

A README that doesn't change with the codebase is worse than no README. It's actively misleading.

Every release should include a README review. Did the API change? Update the examples. Did a new feature become the primary use case? Move it to the top. Did installation requirements change? Fix the instructions.

Treat the README as part of the public API, not as marketing copy. It's documentation that users depend on. Stale documentation is a broken promise.

## Keep documentation aligned with releases

Documentation should describe the version of a package that users actually install. A package's README may evolve alongside its default branch, introducing features or configuration options that aren't available in older releases. Likewise, documentation updates may remove references to functionality that older versions still support.

Packagist's package page isn't a substitute for version-specific documentation. Developers should consult documentation corresponding to their installed version, particularly when working with older releases.

One way to make documentation available alongside the code is to include the `README.md` in the Composer distribution archive. Composer typically installs packages from version-specific `dist` archives, so including the README ensures that each installed release carries the documentation shipped with that version.

For package maintainers, this means avoiding the exclusion of `README.md` from release archives and keeping documentation accurate when tagging releases. A versioned README doesn't guarantee perfect documentation, but it provides a reliable reference point: **the documentation and code were released together.**

## No excuses

"You don't have time to write documentation." — You have time to write code that nobody can figure out how to use.

"It's a small package, the code is self-documenting." — The code is self-documenting to you, after hours of reading it. To someone discovering it for the first time, it's a mystery.

"I'll write it later." — "Later" is when you're feeling productive and the package has zero stars and one fork from yourself.

Write the README. Update it when the code changes. Badge it, example it, link it. Your package is only as good as the first thing people read about it.

There's no shame in shaming yourself into writing a better README.
