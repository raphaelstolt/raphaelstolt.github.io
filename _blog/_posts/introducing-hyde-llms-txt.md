---
title: 'Introducing hyde-llms-txt'
category: php, open-source, tooling
author: raphaelstolt
date: '2026-11-19 11:30'
---

Keeping an `llms.txt` file up to date by hand sounds like one more thing I'll forget to do. So I made [hyde-llms-txt](https://github.com/raphaelstolt/hyde-llms-txt), 
a small extension that generates one for HydePHP sites during the build.

It gathers links to documentation pages and blog posts into a Markdown overview for AI agents. Standard pages can be
included too, and you can exclude routes or customize the title and description.

Getting started with HydePHP 2.0+ is pretty straightforward:

```bash
composer require stolt/hyde-llms-txt
php hyde build
```

The extension is discovered automatically. When there's eligible content, you'll find `llms.txt` in the build output
directory. This blog uses it too: here's [its generated overview](/blog/llms.txt).

One caveat: there's no evidence that search engines or chat assistants use `llms.txt` for rankings or citations. 
Think of it as a handy map for AI agents, not an SEO shortcut.