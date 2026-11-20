@php
    /** @var \Hyde\Pages\MarkdownPost $post */
    $categories = isset($post->category) ? array_filter(array_map('trim', explode(',', $post->category))) : [];
    $categorySlugs = array_map(fn($c) => \Illuminate\Support\Str::slug(str_replace('/', '-', $c)), $categories);
    $readingTimeService = app(\HydeReadingTime\ReadingTimeService::class);
    $readingTime = $readingTimeService->format($readingTimeService->forPage($post));
    $gitShortSha = app(\HydeGitMeta\GitMetaPageService::class)->shortSha($post);
@endphp
<article class="post-card" @if(!empty($categorySlugs)) data-categories="{{ implode(',', $categorySlugs) }}" @endif itemscope itemtype="https://schema.org/BlogPosting">
    <meta itemprop="identifier" content="{{ $post->identifier }}">
    @if($post->getCanonicalUrl())
        <meta itemprop="url" content="{{ $post->getCanonicalUrl()  }}">
    @endif

    <div class="post-meta">
        @if(!empty($categories))
            @foreach($categories as $cat)
                @php($catSlug = \Illuminate\Support\Str::slug(str_replace('/', '-', $cat)))
                <a href="{{ Hyde::relativeLink('index.html') }}#category={{ $catSlug }}" class="post-category-badge" data-category-slug="{{ $catSlug }}">{{ ucfirst($cat) }}</a>
            @endforeach
        @endif
        @isset($post->date)
            <time datetime="{{ $post->date->datetime }}" itemprop="dateCreated datePublished">{{ $post->date->short }}</time>
        @endisset
        @if($readingTime !== null)
            <span>{{ $readingTime }}</span>
        @endif
        @if($gitShortSha)
            <span class="git-commit-hash">{{ $gitShortSha }}</span>
        @endif
    </div>

    <h2 class="post-card-title" itemprop="headline">
        <a href="{{ $post->getRoute() }}">{{ $post->data('title') ?? $post->title }}</a>
    </h2>

    @if($post->data('description') !== null)
        <p class="post-card-excerpt" itemprop="description">
            {{ $post->data('description') }}
        </p>
    @endif

    <div class="post-card-footer">
        <a href="{{ $post->getRoute() }}" class="post-read-more">
            Read article &rarr;
        </a>
    </div>
</article>