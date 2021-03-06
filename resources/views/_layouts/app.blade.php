<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css"
          integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ get_asset('app.css') }}">
    @stack('styles')
</head>
<body>
<div id="app">
    @include('_partials.nav')

    <main class="py-5">
        @includeWhen(session('error'), '_partials.error', ['error' => session('error')])

        @includeWhen(session('success'), '_partials.success', ['success' => session('success')])

        @yield('content')
    </main>
</div>

@include('_partials.cart-store')
@stack('scripts')
<script src="{{ get_asset('app.js') }}"></script>
</body>
</html>
