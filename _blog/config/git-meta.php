<?php

declare(strict_types=1);

use Hyde\Pages\DocumentationPage;
use Hyde\Pages\MarkdownPage;
use Hyde\Pages\MarkdownPost;

return [
    'enabled' => true,
    'short_hash_length' => 8,
    'strict' => false,
    'content_types' => [
        MarkdownPost::class,
    ],
    'repository_url' => 'https://github.com/raphaelstolt/raphaelstolt.github.io',
    'show_author' => false,
    'show_date' => false,
];
