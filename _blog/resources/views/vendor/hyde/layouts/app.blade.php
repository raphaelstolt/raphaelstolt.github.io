<!DOCTYPE html>
<html lang="{{ config('hyde.language', 'en') }}">
<head>
    @include('hyde::layouts.head')
</head>
<body id="app">
    @include('hyde::layouts.navigation')

    @yield('content')

    @include('hyde::layouts.footer')

    @include('hyde::layouts.scripts')
</body>
</html>
