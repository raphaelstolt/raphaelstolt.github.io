{{-- The Post Page Layout --}}
@extends('hyde::layouts.app')
@section('content')

    <main class="site-main">
        <div class="container">
            @include('hyde::components.post.article')
        </div>
    </main>

@endsection