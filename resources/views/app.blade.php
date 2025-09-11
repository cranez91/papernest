<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
      <head>
            <meta charset="utf-8">
            <meta name="viewport"
                  content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <!-- Fetch project name dynamically -->
            <title inertia>{{ config('app.name', 'Laravel') }}</title>
            <script type="application/ld+json">
                  {
                        "@context": "https://schema.org",
                        "@type": "Store",
                        "name": "Papelería Andy",
                        "address": {
                              "@type": "PostalAddress",
                              "addressLocality": "Uriangato",
                              "addressRegion": "Guanajuato",
                              "addressCountry": "MX"
                        },
                        "telephone": "+52-123-456-7890",
                        "url": "https://papeleria-andy.com.mx"
                  }
            </script>

            @vite(['resources/css/app.css', 'resources/js/app.js'])
            @inertiaHead
            @routes
      </head>
      <body class="font-sans antialiased">
            @inertia
      </body>
</html>
