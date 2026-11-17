@php /** @var \Hyde\Pages\MarkdownPost $post */ @endphp
<article class="post-card" itemscope itemtype="https://schema.org/BlogPosting">
    <meta itemprop="identifier" content="{{ $post->identifier }}">
    @if($post->getCanonicalUrl())
        <meta itemprop="url" content="{{ $post->getCanonicalUrl()  }}">
    @endif

    <div class="post-meta">
        @isset($post->category)
            <span class="post-category">{{ ucfirst($post->category) }}</span>
        @endisset
        @isset($post->date)
            <time datetime="{{ $post->date->datetime }}" itemprop="dateCreated datePublished">{{ $post->date->short }}</time>
        @endisset
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