@php
    $navigation = app('navigation.main');
@endphp

<!-- Site Header -->
<header class="site-header">
    <div class="container site-header-inner">
        <a href="{{ Hyde::relativeLink('index.html') }}" class="site-brand">
            <img src="{{ Hyde::relativeLink('media/images/logo.png') }}" alt="{{ config('hyde.name', 'HydePHP') }}" class="site-brand-avatar">
            <span class="site-brand-title">{{ config('hyde.name', 'HydePHP') }}</span>
            <span class="site-brand-badge">Blog</span>
        </a>

        <nav class="site-nav">
            @foreach ($navigation->getItems() as $item)
                @if(!$item instanceof \Hyde\Framework\Features\Navigation\NavigationGroup)
                    <a href="{{ $item->getLink() }}" class="{{ $item->isActive() ? 'active' : '' }}">{{ $item->getLabel() }}</a>
                @endif
            @endforeach
            <a href="https://github.com/raphaelstolt" class="github-link" aria-label="GitHub profile" title="GitHub" target="_blank" rel="noopener noreferrer">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-2.01.465-2.685-.63-2.82-1.23-.09-.315-.6-1.23-1.035-1.485-.36-.21-.885-.72-.015-.735.825-.015 1.425.78 1.62.885.945.66 1.905.48 2.13.36.06-.63.315-1.065.57-1.305-2.01-.255-4.11-1.005-4.11-4.5 0-1.005.36-1.815.945-2.445-.09-.225-.42-1.14.09-2.37 0 0 .78-.24 2.55.915.75-.24 1.56-.36 2.355-.36.795 0 1.605.12 2.355.36 1.77-1.155 2.55-.915 2.55-.915.51 1.23.18 2.145.09 2.37.585.63.945 1.44.945 2.445 0 3.51-2.1 4.245-4.11 4.485.33.285.615.84.615 1.71 0 1.23-.015 2.22-.015 2.52 0 .315.225.69.84.57C20.565 21.795 24 17.31 24 12c0-6.63-5.37-12-12-12z"/>
                </svg>
            </a>
            <button id="theme-toggle" class="theme-toggle-btn" aria-label="Toggle theme" title="Toggle theme">
                <svg class="sun-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="5"></circle>
                    <line x1="12" y1="1" x2="12" y2="3"></line>
                    <line x1="12" y1="21" x2="12" y2="23"></line>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                    <line x1="1" y1="12" x2="3" y2="12"></line>
                    <line x1="21" y1="12" x2="23" y2="12"></line>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                </svg>
                <svg class="moon-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </button>
        </nav>
    </div>
</header>