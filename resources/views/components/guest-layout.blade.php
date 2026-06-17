<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Séries') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="min-h-screen flex flex-col items-center pt-8 sm:justify-center bg-brand-gradient-soft">
            <div class="mb-6">
                <x-application-logo text-class="text-3xl" />
            </div>

            <div class="w-full sm:max-w-md mt-2 px-6 py-8 bg-white shadow-brand overflow-hidden sm:rounded-2xl border border-white">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
