<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LCI Wellbeing')</title>
    <meta name="description" content="@yield('meta_description', 'Trauma-informed therapy and wellbeing support. Calm, grounded, professional care with space to reconnect and move forward.')">
    <meta name="robots" content="index, follow">
    <link rel="icon" href="/favicon.ico">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&family=Playfair+Display:wght@500;600;700&family=Cormorant+Garamond:ital@1&display=swap" rel="stylesheet">

    @if (!app()->runningUnitTests())
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-full bg-cream text-ink-soft antialiased">
    <div class="min-h-screen">
        <x-navbar />

        <main>
            @if (session('status'))
                <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 pt-6">
                    <div class="rounded-2xl bg-emerald-50 px-4 py-3 text-sm text-emerald-900 ring-1 ring-emerald-200/70">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <x-footer />
    </div>
</body>
</html>
