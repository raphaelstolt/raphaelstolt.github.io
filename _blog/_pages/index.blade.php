@php($title = 'Articles & Insights')
@extends('hyde::layouts.app')
@section('content')

    <main class="site-main">
        <div class="container">

            <!-- Page Intro -->
            <section class="page-intro">
                <h1 class="page-intro-title">Articles &amp; Insights</h1>
                <p class="page-intro-lead">
                    Exploring developer tooling, package engineering, and software craftsmanship — building better tools,
                    leaner releases, and smoother developer workflows.
                </p>
            </section>

            <!-- Post Feed -->
            @include('hyde::components.blog-post-feed')

        </div>
    </main>

@endsection
