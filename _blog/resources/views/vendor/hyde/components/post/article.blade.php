<article class="post-article" id="{{ $page->identifier }}" itemscope itemtype="https://schema.org/BlogPosting">
    <meta itemprop="identifier" content="{{ $page->identifier }}">
    @if($page->getCanonicalUrl() !== null)
        <meta itemprop="url" content="{{ $page->getCanonicalUrl() }}">
    @endif

    <!-- Post Header -->
    <header class="post-header">
        <div class="post-meta">
            @isset($page->category)
                @foreach(array_filter(array_map('trim', explode(',', $page->category))) as $cat)
                    @php($catSlug = \Illuminate\Support\Str::slug(str_replace('/', '-', $cat)))
                    <a href="{{ Hyde::relativeLink('index.html') }}#category={{ $catSlug }}" class="post-category-badge" data-category-slug="{{ $catSlug }}">{{ ucfirst($cat) }}</a>
                @endforeach
            @endisset
            @isset($page->date)
                <time datetime="{{ $page->date->datetime }}" itemprop="dateCreated datePublished">{{ $page->date->format('F j, Y') }}</time>
            @endisset
        </div>

        <h1 class="post-title" itemprop="headline">{{ $page->title }}</h1>

        @isset($page->author)
        <div class="post-author-box">
            <div>
                <div class="post-author-name" itemprop="author" itemscope itemtype="https://schema.org/Person">
                    <span itemprop="name">{{ $page->author->name ?? $page->author->username }}</span>
                </div>
            </div>
        </div>
        @endisset
    </header>

    <!-- Post Content Body -->
    <div class="post-content" aria-label="Article body" itemprop="articleBody">
        {{ $content }}
    </div>

</article>