<script setup>
    import { Head, usePage } from '@inertiajs/vue3'

    // Props que recibirá el componente
    const props = defineProps({
        title: {
            type: String,
            default: '',
        },
        description: {
            type: String,
            default: 'Papelería Andy, tu tienda en línea de confianza.',
        },
        image: {
            type: String,
            default: '/images/default-og.jpg',
        },
        url: {
            type: String,
            default: null,
        },
    })

    const siteName = 'Papelería Andy'

    // Obtener baseUrl desde Inertia (APP_URL del .env)
    const page = usePage()
    const baseUrl = page.props.appUrl

    // Valores calculados
    const fullTitle = props.title ? `${props.title} | ${siteName}` : siteName
    const fullUrl = props.url || `${baseUrl}${window.location.pathname}`
</script>

<template>
  <Head>
    <title>{{ fullTitle }}</title>
    <meta name="description" :content="description" />
    <link rel="canonical" :href="fullUrl" />

    <!-- Open Graph -->
    <meta property="og:title" :content="fullTitle" />
    <meta property="og:description" :content="description" />
    <meta property="og:image" :content="image" />
    <meta property="og:type" content="website" />
    <meta property="og:url" :content="fullUrl" />
    <meta property="og:site_name" :content="siteName" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" :content="fullTitle" />
    <meta name="twitter:description" :content="description" />
    <meta name="twitter:image" :content="image" />
  </Head>
</template>
