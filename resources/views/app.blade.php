<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
      <head>
            <meta charset="utf-8">
            <meta name="viewport"
                  content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <!-- Fetch project name dynamically -->
            <title inertia>{{ config('app.name', 'Laravel') }}</title>
            <!--link rel="stylesheet"
                  href="https://cdn.jsdelivr.net/npm/tailwindcss@4.1.0/dist/tailwind.min.css"-->
            <!--script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1"
                       type="module">
            </script-->
            @vite(['resources/css/app.css', 'resources/js/app.js'])
            @inertiaHead
            @routes
      </head>
      <body class="font-sans antialiased">
            @inertia
      </body>
</html>
