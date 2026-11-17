# Publishing the Blog to GitHub Pages

This blog is built with [HydePHP](https://hydephp.com), a static site generator. The built output is plain HTML/CSS/JS that GitHub Pages can serve directly.

## Repository Setup

This repository (`raphaelstolt.github.io`) is a **user site** repository, which means GitHub Pages serves content from the `main` branch root.

The root `index.html` file serves as your personal landing page, while the blog is built into a `blog/` folder and served at a sub-path:
- **Root page:** `https://raphaelstolt.github.io/` (served from `index.html` in the repository root)
- **Blog URL:** `https://raphaelstolt.github.io/blog/` (served from the built `blog/` folder)
- **Source:** `_blog/` directory in this repository
- **Build output:** `_blog/_site/` → copied to `blog/` folder

## GitHub Actions (Automatic Builds)

A GitHub Actions workflow automatically builds the blog on every push to `main` and commits the output to the `blog/` folder.

### Step 1: Enable GitHub Pages

1. Go to your repository on GitHub → **Settings** → **Pages**
2. Under **Source**, select **Deploy from a branch**
3. Select the **`main` branch** and **`/ (root)`** folder
4. Save

### Step 2: Workflow File

The workflow file `.github/workflows/deploy.yml` is already configured. It:
- Checks out the repository
- Installs PHP and Node.js dependencies
- Compiles CSS with Vite
- Builds the static site with `php hyde build`
- Copies the built output to the `blog/` folder
- Commits and pushes the changes back to `main` (with `[skip ci]` to avoid infinite loops)

### Step 3: Push and Deploy

Commit and push your changes:

```bash
git add .
git commit -m "Update blog"
git push
```

The workflow will run automatically. You can check its progress under **Actions** → **Build Blog** in your repository.

### Step 4: Verify

After the workflow completes:
- Visit `https://raphaelstolt.github.io/` to see your root landing page
- Visit `https://raphaelstolt.github.io/blog/` to see your blog

## Manual Build

If you prefer to build locally instead of using the automated workflow:

### Prerequisites

- **PHP 8.2+** installed
- **Node.js 18+** and **npm** installed

### Build Steps

```bash
# 1. Install dependencies (first time only)
cd _blog
composer install
npm install

# 2. Compile CSS
npm run build

# 3. Build the static site
php hyde build

# 4. Copy to blog folder (from repository root)
cd ..
rm -rf blog
cp -r _blog/_site blog
```

### Deploy

```bash
git add blog/
git commit -m "Update blog"
git push
```

> **Note:** The root `index.html` remains untouched in the repository root. GitHub Pages serves it alongside the `blog/` folder.

## Adding a New Blog Post

1. Create a new Markdown file in `_blog/_posts/`:

```bash
cd _blog
php hyde make:post "My New Post"
```

2. Edit the generated file in `_blog/_posts/my-new-post.md`.

3. Commit and push — the GitHub Actions workflow will rebuild and deploy automatically.

## Custom Domain (Optional)

To use a custom domain:

1. In GitHub → **Settings** → **Pages** → **Custom domain**, enter your domain
2. Add a `CNAME` file to `_blog/_pages/CNAME` containing your domain
3. Rebuild and push

## Troubleshooting

| Problem | Solution |
|---------|----------|
| Root `index.html` not found | Make sure GitHub Pages source is set to `main` branch `/ (root)` folder |
| 404 on `/blog/` | Make sure the workflow ran successfully and the `blog/` folder exists in the repo |
| CSS not loading | Run `npm run build` before `php hyde build` to compile the CSS |
| Deprecated warnings | These are suppressed in the `hyde` entry point; if they appear, check the `hyde` file |
| Avatar missing | Ensure `_blog/_media/images/logo.png` exists before building |
| Infinite workflow loop | The `[skip ci]` tag in the commit message prevents this; don't remove it |
