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