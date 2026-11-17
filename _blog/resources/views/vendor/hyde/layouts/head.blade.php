<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $page->title() }}</title>

@if (Asset::exists('favicon.ico'))
    <link rel="shortcut icon" href="{{ Asset::get('media/favicon.ico') }}" type="image/x-icon">
@endif

{{-- App Meta Tags --}}
@include('hyde::layouts.meta')

{{-- Google Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

{{-- App Stylesheets --}}
@include('hyde::layouts.styles')

{{-- Theme detection script to avoid FOUC --}}
<script>
  (function() {
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = savedTheme || (prefersDark ? 'dark' : 'light');
    document.documentElement.setAttribute('data-theme', theme);
    if (theme === 'dark') {
      document.documentElement.classList.add('dark');
    }
  })();
</script>

{{-- Add any extra code to include before the closing <head> tag --}}
@stack('head')

{{-- If the user has defined any custom head tags, render them here --}}
{!! config('hyde.head') !!}
{!! Includes::html('head') !!}
