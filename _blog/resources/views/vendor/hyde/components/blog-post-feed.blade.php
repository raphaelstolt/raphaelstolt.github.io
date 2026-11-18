@php
    $allPosts = $posts ?? \Hyde\Pages\MarkdownPost::getLatestPosts();
    $categories = [];
    foreach ($allPosts as $post) {
        if (!empty($post->category)) {
            $postCats = array_filter(array_map('trim', explode(',', $post->category)));
            foreach ($postCats as $cat) {
                $slug = \Illuminate\Support\Str::slug(str_replace('/', '-', $cat));
                if (!isset($categories[$slug])) {
                    $categories[$slug] = [
                        'name' => ucfirst($cat),
                        'slug' => $slug,
                        'count' => 0,
                    ];
                }
                $categories[$slug]['count']++;
            }
        }
    }
    uasort($categories, fn($a, $b) => strcasecmp($a['name'], $b['name']));
@endphp

@if(count($categories) > 0)
    <!-- Category Filter Pills Bar -->
    <nav class="category-filter-nav" aria-label="Filter articles by category">
        <div class="category-pills">
            <a href="#category=all" class="category-pill active" data-category="all" aria-pressed="true">
                All <span class="category-pill-count">{{ $allPosts->count() }}</span>
            </a>
            @foreach($categories as $cat)
                <a href="#category={{ $cat['slug'] }}" class="category-pill" data-category="{{ $cat['slug'] }}" aria-pressed="false">
                    {{ $cat['name'] }} <span class="category-pill-count">{{ $cat['count'] }}</span>
                </a>
            @endforeach
        </div>
        <div id="category-filter-status" class="category-filter-status" style="display: none;" aria-live="polite">
            <span class="category-filter-message"></span>
            <a href="#category=all" class="category-filter-clear">Clear filter &times;</a>
        </div>
    </nav>
@endif

<!-- Post Feed -->
<section class="post-list" id="post-list">
    @foreach($allPosts as $post)
        @include('hyde::components.article-excerpt')
    @endforeach
</section>

<div id="no-posts-matched" class="no-posts-matched" style="display: none;">
    <p>No articles found in this category.</p>
    <a href="#category=all" class="category-filter-reset-btn">View all articles</a>
</div>