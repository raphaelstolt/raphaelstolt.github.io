<?php

declare(strict_types=1);

/**
 * Configuration for the hyde-llms-txt extension.
 *
 * This file defines the default configuration for generating llms.txt
 * during a HydePHP build. You can publish this file to your project's
 * config directory and modify it to customize the output.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Enable llms.txt Generation
    |--------------------------------------------------------------------------
    |
    | Set to false to disable automatic llms.txt generation during builds.
    | When disabled, the build task will not run and no file will be written.
    |
    */

    'enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Document Title
    |--------------------------------------------------------------------------
    |
    | The title of the generated llms.txt document. This appears as the
    | top-level heading in the file. Defaults to the HydePHP site name.
    |
    */

    'title' => 'Articles & Insights by Raphael Stolt',

    /*
    |--------------------------------------------------------------------------
    | Document Description
    |--------------------------------------------------------------------------
    |
    | A short description of the site, displayed as a blockquote below
    | the title. Leave empty to omit.
    |
    */

    'description' => 'Blog about Exploring developer tooling, package engineering, and software craftsmanship — building better tools, leaner releases, and smoother developer workflows.',

    /*
    |--------------------------------------------------------------------------
    | Content Inclusion
    |--------------------------------------------------------------------------
    |
    | Control which types of content are included in the generated file.
    | - docs: Documentation pages (from _docs directory)
    | - posts: Blog posts (from _posts directory)
    | - pages: Standard pages (from _pages directory)
    |
    */

    'include' => [
        'docs' => true,
        'posts' => true,
        'pages' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Excluded Routes
    |--------------------------------------------------------------------------
    |
    | List of route keys to exclude from the generated file.
    | Route keys are the URL paths without file extensions.
    | For example, 'docs/installation' excludes the installation guide.
    |
    */

    'exclude' => [],
];
