<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'QR Code Verification')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    @stack('styles')
</head>
<body>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>

