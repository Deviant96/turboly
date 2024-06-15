<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task app</title>

    @yield('styles')
</head>

<body>
    <div class="main">
        @yield('content')
    </div>

    <script src="{{ asset('js/auth.js') }}"></script>
    @yield('scripts')
</body>

</html>
