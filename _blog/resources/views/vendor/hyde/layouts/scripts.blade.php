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

{{-- Category filter handling --}}
<script>
    (function () {
      function getFilterSlug() {
        const hash = window.location.hash || '';
        const match = hash.match(/category=([^&]+)/);
        if (match) {
          return decodeURIComponent(match[1]).trim().toLowerCase();
        }
        return null;
      }

      function applyCategoryFilter() {
        const postList = document.getElementById('post-list') || document.querySelector('.post-list');
        if (!postList) return;

        const targetSlug = getFilterSlug();
        const cards = postList.querySelectorAll('.post-card');
        const pills = document.querySelectorAll('.category-pill');
        const statusEl = document.getElementById('category-filter-status');
        const messageEl = statusEl ? statusEl.querySelector('.category-filter-message') : null;
        const noPostsEl = document.getElementById('no-posts-matched');

        const isAll = !targetSlug || targetSlug === 'all';
        let visibleCount = 0;

        cards.forEach(function (card) {
          const rawCats = card.getAttribute('data-categories') || '';
          const cardCats = rawCats.split(',').map(function (s) { return s.trim().toLowerCase(); }).filter(Boolean);
          if (isAll || cardCats.includes(targetSlug)) {
            card.style.display = '';
            visibleCount++;
          } else {
            card.style.display = 'none';
          }
        });

        pills.forEach(function (pill) {
          const pillCat = (pill.getAttribute('data-category') || '').toLowerCase();
          const active = isAll ? (pillCat === 'all') : (pillCat === targetSlug);
          pill.classList.toggle('active', active);
          pill.setAttribute('aria-pressed', active ? 'true' : 'false');
        });

        if (statusEl && messageEl) {
          if (!isAll) {
            const activePill = document.querySelector('.category-pill[data-category="' + targetSlug + '"]');
            let categoryLabel = targetSlug;
            if (activePill) {
              const clone = activePill.cloneNode(true);
              const countSpan = clone.querySelector('.category-pill-count');
              if (countSpan) countSpan.remove();
              categoryLabel = clone.textContent.trim();
            }
            messageEl.textContent = 'Showing ' + visibleCount + ' article' + (visibleCount === 1 ? '' : 's') + ' tagged with “' + categoryLabel + '”';
            statusEl.style.display = 'flex';
          } else {
            statusEl.style.display = 'none';
          }
        }

        if (noPostsEl) {
          noPostsEl.style.display = (visibleCount === 0) ? 'block' : 'none';
        }
      }

      window.addEventListener('hashchange', applyCategoryFilter);
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', applyCategoryFilter);
      } else {
        applyCategoryFilter();
      }
    })();
</script>

{{-- Add any extra scripts to include before the closing <body> tag --}}
@stack('scripts')

{{-- If the user has defined any custom scripts, render them here --}}
{!! config('hyde.scripts') !!}
{!! Includes::html('scripts') !!}
