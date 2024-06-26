<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if($isMobile)
        <link rel="stylesheet" href="{{ asset('css/mobile/main.css') }}">
    @elseif($isTablet)
        <meta />
    @else
        <link rel="stylesheet" href="{{ asset('css/desktop/main.css') }}">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task app</title>

    @yield('styles')
</head>

<body>
    @if (auth()->check())
        <nav class="nav">
            <div class="greet-message">Hi, {{ Auth::user()->username }}</div>
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