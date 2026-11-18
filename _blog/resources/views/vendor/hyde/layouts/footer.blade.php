<!-- Site Footer -->
<footer class="site-footer">
    <div class="container site-footer-inner">
        <div>
            &copy; {{ date('Y') }} {{ config('hyde.name', 'HydePHP') }}. Built with AI and simplicity in mind.
        </div>
        <div class="site-footer-links">
            <a href="{{ Hyde::relativeLink('index.html') }}">All Posts</a>
        </div>
    </div>
</footer>