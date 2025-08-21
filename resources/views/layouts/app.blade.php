<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
      <head>
            <meta charset="utf-8">
            <meta name="viewport"
                  content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <title>@yield('title', 'Papelería')</title>
            <link rel="stylesheet"
                  href="https://cdn.jsdelivr.net/npm/tailwindcss@4.1.0/dist/tailwind.min.css">
            @vite(['resources/css/app.css', 'resources/js/app.js'])
      </head>
      <body>
            <div id="app">
                @yield('content')
            </div>
      </body>
</html>
