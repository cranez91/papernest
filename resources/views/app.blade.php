<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
      <head>
            <meta charset="utf-8">
            <meta name="viewport"
                  content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="icon"
                  href="/images/papeleria-andy-logo-dino.png">
            <!-- Fetch project name dynamically -->
            <title inertia>{{ config('app.name', 'Laravel') }}</title>
            @vite(['resources/css/app.css', 'resources/js/app.js'])
            @inertiaHead
            @routes
      </head>
      <body class="font-sans antialiased">
            @inertia
      </body>
</html>
