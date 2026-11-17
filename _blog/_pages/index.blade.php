@php($title = 'Articles & Insights')
@extends('hyde::layouts.app')
@section('content')

    <main class="site-main">
        <div class="container">

            <!-- Page Intro -->
            <section class="page-intro">
                <h1 class="page-intro-title">Articles &amp; Insights</h1>
                <p class="page-intro-lead">
                    Writing about polyglot software development, static sites, architecture, and developer productivity.
                </p>
            </section>

            <!-- Post Feed -->
            @include('hyde::components.blog-post-feed')

        </div>
    </main>

@endsection
