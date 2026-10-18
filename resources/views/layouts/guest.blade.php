<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}" />
    <title>{{ $title ?? 'EcoRisk — Pengelolaan Sampah Berbasis Risiko' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="@yield('body-class', 'min-h-screen bg-slate-50 font-sans text-slate-800 antialiased')">
    @yield('content')
</body>

</html>
