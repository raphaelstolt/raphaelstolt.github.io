---
title: 'Building a Reading-Time Extension for HydePHP'
category: php, open-source, hydephp
author: raphaelstolt
date: '2026-11-19 20:19'
---

A small detail can make a blog post easier to approach: telling readers roughly how long it will take to read.

I wanted that detail on [my blog](https://raphaelstolt.github.io/blog), which is built with [HydePHP](https://hydephp.com/). Rather than hard-code a reading-time label into every post, I built `hyde-reading-time`, a reusable extension that estimates reading time from Markdown content.

The interesting part isn't dividing a word count by a number. It's deciding **what counts as readable content**, where the calculation belongs, and how to keep a static-site feature simple.

## Start with the smallest useful formula

The basic estimate is straightforward:

```text
reading time in minutes = word count / words per minute
```

For a reader-facing estimate, I prefer rounding up. A short article shouldn't display “0 min read.”

Here's a framework-independent PHP example of the calculation:

```php
function estimateReadingMinutes(int $wordCount, int $wordsPerMinute = 200): int
{
    if ($wordsPerMinute <= 0) {
        throw new InvalidArgumentException('Reading speed must be positive.');
    }

    return max(1, (int) ceil($wordCount / $wordsPerMinute));
}
```

At 200 words per minute, a 750-word article is estimated at four minutes. The reading speed is a configurable heuristic, not a measurement of any particular reader.

That formula is intentionally boring. The input is where things get interesting.

## Markdown isn't plain prose

Counting whitespace-separated tokens in the original Markdown source produces misleading results. Consider this example:

````markdown
---
title: A post about PHP
---

# A post about PHP

Read the [documentation](https://example.com/docs).

```php
$package = 'hyde-reading-time';
```
````

A naïve counter might include front matter, link destinations, code, and Markdown delimiters. Those are useful to the renderer, but they're not all words the reader is expected to read as prose.

I wanted the estimate to reflect the **article**, not the syntax used to describe it.

A practical approach is to separate content processing from estimation:

1. Remove or ignore front matter.
2. Parse Markdown using a Markdown-aware library rather than a pile of regular expressions.
3. Decide explicitly whether fenced and inline code count toward reading time.
4. Extract readable text from headings, paragraphs, and other relevant nodes.
5. Count words and apply the configured reading speed.

Those are design guidelines, not a claim about the internal classes or parsing strategy of the released extension. The important thing is to make the policy explicit and testable.

For a technical blog, code blocks deserve special consideration. Readers may spend **more** time understanding code than prose, even though the code contains relatively few natural-language words. Excluding code makes the estimate predictable, but it can underestimate the time needed for code-heavy tutorials. A future enhancement could apply a separate code-reading heuristic.

## Why a HydePHP extension?

HydePHP already handles the important work of turning Markdown into a static website. Reading time is derived from the same source content, so it makes sense to calculate it as part of the site's content or build workflow rather than maintaining it manually.

The separation of responsibilities is useful:

- **HydePHP** owns content discovery, page metadata, rendering, and static-site generation.
- **`hyde-reading-time`** owns the reading-time estimate and its integration with HydePHP.
- **The site's templates** decide where and how to display the result.

That last point matters. An extension should provide a value without dictating the entire design of a blog post header.

For example, a template might present the result as:

```text
Nov 19, 2026 · 4 min read
```

The exact template API depends on the installed extension version, so I won't invent a Blade directive or helper name here. The general principle is to expose the estimate to templates and let the site control its presentation.

## Make the behavior configurable, not complicated

A reasonable default is **200 words per minute**, but different sites and audiences may want different values. Documentation, essays, and code-heavy tutorials aren't read in quite the same way.

I'd keep the configuration surface small: reading speed, the content types to which estimates apply, and—if needed—the treatment of code blocks.

More options aren't automatically better. Every new configuration key is another behavior to explain, test, and maintain.

## Test the edge cases, not just the happy path

A reading-time estimator is small enough to test thoroughly. The cases I care about include empty content, very short posts, front matter, headings, links, fenced code, inline code, and unusual whitespace.

There are also two easily overlooked cases:

**Unicode text.** Word counting is more nuanced than splitting on ASCII spaces. A multilingual site needs an explicit policy and tests for the languages it supports.

**Invalid reading speeds.** A zero or negative words-per-minute setting must fail clearly rather than producing an invalid calculation.

Separating the estimator from the HydePHP integration makes these cases easier to test without booting an entire site for every assertion. Integration tests can then verify that the computed value is available where the templates need it.

## Static sites benefit from build-time features

One reason I like this approach is that the reading-time label doesn't need an external API, visitor tracking, or browser-side computation.

The estimate can be derived from content that already exists in the repository. That makes it a natural fit for a static blog: the result can be generated alongside the page and displayed as ordinary HTML.

It's also a reminder that not every feature needs JavaScript. Sometimes the best runtime dependency is no runtime dependency at all.

## Shipping a small extension

`hyde-reading-time` is now implemented and integrated into my HydePHP based [blog](https://raphaelstolt.github.io/blog).

The project follows a pattern I enjoy: identify a small, repeatable developer-experience problem, build a focused tool
around it, and use it in a real project. Dogfooding exposes rough edges that are easy to miss in a standalone demo.

The most useful lesson from this project is that **a tiny feature can still deserve a clean boundary**. The formula is
one line; the decisions about Markdown, configuration, integration, and testing are what make it reusable.

If you're building a HydePHP blog or documentation site, a reading-time estimate is a small addition that gives readers
useful context before they dive in.

*This article describes the design considerations behind `hyde-reading-time`. The standalone PHP example is illustrative; consult the extension's source and documentation for its exact public API.*
