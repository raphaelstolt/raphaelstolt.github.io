---
title: 'Introducing hyde-git-meta — Git commit metadata for HydePHP'
category: oss, hydephp, php
author: raphaelstolt
date: '2026-11-21 09:00'
---

[hyde-git-meta](https://github.com/raphaelstolt/hyde-git-meta) is a small extension for [HydePHP](https://hydephp.dev) that displays the Git commit that last modified each blog post, documentation page, or Markdown page at build time.

## The problem

HydePHP is excellent for building static sites from Markdown, but it doesn't track which Git commit last changed a content file. If you want to show readers which commit touched a post — for traceability, debugging, or just transparency — you have to build that yourself.

I needed this for my own blog. Every post should show the commit hash that last modified it, so readers can see exactly what changed and when, without navigating to GitHub.

## What it does

The extension resolves the most recent Git commit for each Markdown source file during the HydePHP build process. It runs `git log` once per file, caches the results, and exposes the data to Blade templates through simple directives.

Three directives are available:

- `@gitCommitHash` — outputs the abbreviated commit hash (e.g. `a3f91c2d`)
- `@gitCommitUrl` — outputs the full commit URL
- `@gitCommitLink` — renders a styled link or plain hash, depending on configuration

In use, it looks like this:

```blade
<div class="post-meta">
    Nov 19, 2026 &middot; 5 min read &middot; @gitCommitLink
</div>
```

For programmatic access, the `GitMetaPageService` returns a `GitCommitInfo` object with properties like `fullSha`, `shortSha`, `timestamp`, `authorName`, `authorEmail`, and `subject`.

## Design decisions

A few choices shaped the package:

**Build-time only.** Metadata is resolved during `php hyde build`, not at runtime. The generated HTML contains static text — no JavaScript, no API calls, no PHP execution on the hosting server. This keeps the static site truly static.

**Per-file resolution.** Each file's last-modifying commit is resolved independently. Two posts changed in different commits show different hashes. This is more useful than a single "last deployed" commit.

**Subdirectory support.** The HydePHP project can live in a subdirectory of a larger Git repository. The extension walks up from each source file to find the `.git` directory, so it works whether your Hyde site is at the repo root or nested deeper. This was important for my own setup, where the blog source lives in `_blog/` within a broader personal site repository.

**Safe Git execution.** All Git commands use argument-vector invocation through Symfony Process. File paths are never interpolated into shell commands, preventing injection attacks from malicious filenames.

**Opt-in display.** The directives do nothing for unsupported page types or when Git data is unavailable. Untracked files, empty repositories, and non-Git environments simply render nothing — no errors, no broken HTML.

## Configuration

The extension is minimal to configure. Install it with Composer:

```bash
composer require stolt/hyde-git-meta
```

Then optionally publish the config:

```bash
php hyde vendor:publish --tag=git-meta-config
```

The config lets you set the short hash length, enable strict mode for CI, choose which page types receive metadata, and configure a repository URL for generating commit links. SSH URLs are normalized to HTTPS automatically, and GitHub, GitLab, and Bitbucket are supported out of the box.

## Limitations

Shallow clones may lack per-file history, so CI workflows should use `fetch-depth: 0`. Squashed history and rebased branches won't reflect the original commit timeline. And commit subjects may contain sensitive information — review your messages before exposing them.

## Why build it

I could have hardcoded commit hashes or written a custom build script. But the problem is general enough that other HydePHP users might benefit, and packaging it as a proper extension means it integrates cleanly with Hyde's service container, Blade templating, and page lifecycle.

The package is open source and MIT-licensed. Check it out at [raphaelstolt/hyde-git-meta](https://github.com/raphaelstolt/hyde-git-meta).
