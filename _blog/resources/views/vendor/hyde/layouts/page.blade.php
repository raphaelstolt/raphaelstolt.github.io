{{-- The Markdown Page Layout --}}
@extends('hyde::layouts.app')
@section('content')

    <main class="site-main">
        <div class="container">
            <article class="post-content">
                {{ $content }}
            </article>
        </div>
    </main>

@endsection