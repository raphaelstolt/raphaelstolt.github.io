---
title: "Gather requirements in a PROMPTS.md"
category: oss, workflow, ai
author: raphaelstolt
date: '2026-11-19 03:00'
---

*Before writing a single line of code, I write a single file that tells AI exactly what to build.*

When starting a new project, the hardest part isn't implementation — it's knowing what to implement. Requirements drift, scope creeps, and half-baked ideas lead to abandoned repositories.

My solution is simple: a `PROMPTS.md` file at the root of every new Git repository.

## The idea

Before I open an IDE or run a single command, I create a bare repository and add one file: `PROMPTS.md`.

This file is where I gather every requirement, constraint, and design decision for the project. It's not a formal specification. It's a living document written in plain language that serves as the single source of truth for what the project should become.

Think of it as a conversation with your future self — or more accurately, with the AI agent that will do most of the actual work.

## How it works

The workflow is straightforward:

1. **Create the repository.** Empty, no code, just `PROMPTS.md`.
2. **Write the requirements.** Every feature, constraint, and non-goal goes into the file as a clear, actionable item.
3. **Feed it to an agentic workflow.** Open the repository in an AI-powered IDE or agent, and let it work through the items in `PROMPTS.md` one by one.
4. **Iterate.** As the agent implements each item, new requirements or refinements emerge. Add them to `PROMPTS.md` and continue.

The file becomes both the input and the progress tracker. Completed items can be checked off or moved to a "Done" section, while new discoveries are appended as they arise.

## Why PROMPTS.md works

There are three reasons this approach is effective.

**First, it forces clarity before code.** Writing requirements in a Markdown file is slower than typing prompts into a chat window. That slowness is the point. It gives you time to think about edge cases, naming, and architecture before the agent starts generating code you'll later have to undo.

**Second, it's version-controlled.** Every change to `PROMPTS.md` is tracked in Git. You can see how the requirements evolved, when scope was added or removed, and why certain decisions were made. This is invaluable when revisiting a project months later.

**Third, it's AI-native.** Unlike traditional project management tools, `PROMPTS.md` lives in the repository alongside the code. AI agents can read it, reference it, and work from it without any external tooling or API keys. The requirements and the implementation share the same context.

## A practical example

Here's what a `PROMPTS.md` might look like for a small PHP package:

```markdown
# Package: config-cartographer

## Goal
A CLI tool that analyzes a PHP project's configuration files and generates a visual dependency graph.

## Requirements
- [ ] Parse `composer.json` to identify package dependencies
- [ ] Scan `config/` directories for PHP configuration files
- [ ] Detect cross-references between config files
- [ ] Output a Mermaid diagram of the dependency graph
- [ ] Support Laravel and Symfony config structures

## Constraints
- PHP 8.2+ only
- No runtime dependencies beyond what's already in a typical project
- Must work on a fresh clone without installation

## Non-goals
- Not a static analysis tool
- Not a replacement for IDE navigation
- Does not modify any files
```

Each checked item represents completed work. The unchecked items are the agent's todo list. As implementation reveals new needs — say, handling nested config arrays — they're added as new items.

## The agentic workflow

The magic happens when you feed `PROMPTS.md` to an AI agent.

Instead of vague, iterative prompting in a chat interface, the agent reads the file and works through it systematically. It implements the first requirement, writes tests, moves to the next item, and so on. When it encounters ambiguity, it can reference the constraints and non-goals section to make decisions aligned with your intent.

This is fundamentally different from the typical "prompt, review, fix, repeat" cycle. The agent has context from the start. It knows what not to build. It understands the boundaries.

## When it doesn't work

Not every project benefits from this approach.

If you're building something exploratory — a quick prototype, a proof of concept, or a personal script — `PROMPTS.md` is overkill. The technique shines when the project has enough complexity that requirements matter but is small enough that a single Markdown file can hold them.

It also requires discipline. If you treat `PROMPTS.md` as a one-time exercise and never update it, it becomes stale noise. The file only works if you maintain it alongside the code.

## The sweet spot

I use `PROMPTS.md` for every new PHP package, CLI tool, or small application. It's the difference between starting with a clear direction and starting with a vague idea that slowly morphs into something unrecognizable.

The file takes ten minutes to write. It saves hours of rework.

## Try it

Next time you start a new project, resist the urge to write code first. Create the repository, add `PROMPTS.md`, and spend ten minutes writing down what you actually want to build.

Then let an AI agent do the rest.

You'll be surprised how much clearer your thinking becomes when you're forced to articulate requirements before you see them materialize in code.
