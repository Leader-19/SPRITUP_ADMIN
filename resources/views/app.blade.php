<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      @class(['dark' => ($appearance ?? 'system') === 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Dark mode system detection --}}
    <script>
        (function () {
            const appearance = '{{ $appearance ?? "system" }}';

            if (appearance === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (prefersDark) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>

    {{-- Theme background --}}
    <style>
        html {
            background-color: oklch(1 0 0);
        }

        html.dark {
            background-color: oklch(0.145 0 0);
        }
    </style>

    {{-- Title --}}
    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    {{-- Favicon --}}
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    {{-- Fonts (safe for production) --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Battambang&display=swap" rel="stylesheet">

    {{-- Preload fonts --}}
    <link rel="preload"
          href="https://fonts.bunny.net/instrument-sans/files/instrument-sans-latin-500-normal.woff2"
          as="font"
          type="font/woff2"
          crossorigin>

    <link rel="preload"
          href="https://fonts.bunny.net/instrument-sans/files/instrument-sans-latin-600-normal.woff2"
          as="font"
          type="font/woff2"
          crossorigin>

    {{-- Vite (ONLY ONCE - FIXED) --}}
    @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])

    {{-- Inertia --}}
    @inertiaHead
    @routes

</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
