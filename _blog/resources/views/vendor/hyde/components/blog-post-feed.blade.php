<!-- Post Feed -->
<section class="post-list">
    @foreach($posts ?? MarkdownPost::getLatestPosts() as $post)
        @include('hyde::components.article-excerpt')
    @endforeach
</section>