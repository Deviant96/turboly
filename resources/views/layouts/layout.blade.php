<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/mobile/main.css') }}" media="only screen and (max-width: 600px)">
    <link rel="stylesheet" href="{{ asset('css/tablet/main.css') }}"
        media="only screen and (min-width: 601px) and (max-width: 1024px)">
    <link rel="stylesheet" href="{{ asset('css/desktop/main.css') }}" media="only screen and (min-width: 1025px)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task app</title>

    @yield('styles')
</head>

<body>
    @if (auth()->check())
        <nav class="nav">
            <div>Hi, {{ Auth::user()->username }}</div>
            <div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-button">Logout</button>
                </form>
            </div>
        </nav>
    @endif
    <div class="main">
        @yield('content')
    </div>

    <script src="{{ asset('js/auth.js') }}"></script>
    @yield('scripts')
</body>

</html>