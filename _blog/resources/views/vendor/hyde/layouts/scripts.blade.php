{{-- The compiled Vite scripts --}}
@if(Vite::running())
    {{ Vite::assets(['resources/assets/app.js']) }}
@elseif(Asset::exists('app.js'))
    <script type="module" defer src="{{ Asset::get('app.js') }}"></script>
@endif

{{-- Theme toggle handling --}}
<script>
    const toggleBtn = document.getElementById('theme-toggle');
    const root = document.documentElement;

    function getPreferredTheme() {
      const saved = localStorage.getItem('theme');
      if (saved) return saved;
      return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    function setTheme(theme) {
      root.setAttribute('data-theme', theme);
      localStorage.setItem('theme', theme);
      if (theme === 'dark') {
        root.classList.add('dark');
      } else {
        root.classList.remove('dark');
      }
    }

    if (toggleBtn) {
      toggleBtn.addEventListener('click', () => {
        const current = root.getAttribute('data-theme') || getPreferredTheme();
        const nextTheme = current === 'dark' ? 'light' : 'dark';
        setTheme(nextTheme);
      });
    }
</script>

{{-- Add any extra scripts to include before the closing <body> tag --}}
@stack('scripts')

{{-- If the user has defined any custom scripts, render them here --}}
{!! config('hyde.scripts') !!}
{!! Includes::html('scripts') !!}
