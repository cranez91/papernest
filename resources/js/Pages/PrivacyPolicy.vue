
<template>
    <SeoHead title="Política de Privacidad"
             description="En Papelería Andy, valoramos tu confianza y nos comprometemos a proteger la información personal que compartes con nosotros."
             image="/images/preview-cart.jpg"/>

    <Header/>

    <div class="max-w-3xl mx-auto py-10 px-6 prose prose-slate pt-26">
        <h1 class="text-3xl font-bold mb-6">
            Política de Privacidad
        </h1>

        <p>
            En <strong>Papelería Andy</strong> valoramos tu confianza y nos comprometemos a proteger
            la información personal que compartes con nosotros. Esta política explica qué datos
            recolectamos, cómo los utilizamos y cuáles son tus derechos.
        </p>

        <h2 class="font-bold pt-4">1. Información que recopilamos</h2>
        <ul>
            <li>Nombre y apellidos.</li>
            <li>Dirección de entrega.</li>
            <li>Número de teléfono y datos de contacto.</li>
            <li>Detalles de los pedidos realizados.</li>
        </ul>
        <p>No recopilamos información financiera, ya que los pagos se realizan únicamente en efectivo contra entrega.</p>

        <h2 class="font-bold pt-4">2. Uso de la información</h2>
        <ul>
            <li>Procesar y entregar tus pedidos.</li>
            <li>Contactarte en caso de dudas, cambios o incidencias con la entrega.</li>
            <li>Mejorar la experiencia de compra en nuestro sitio web.</li>
        </ul>

        <h2 class="font-bold pt-4">3. Compartición de datos</h2>
        <p>
            No compartimos tu información personal con terceros, salvo en los siguientes casos:
        </p>
        <ul>
            <li>Cuando sea necesario para cumplir con la entrega (ej. repartidores internos o externos).</li>
            <li>Cuando lo requiera la ley o autoridades competentes.</li>
        </ul>

        <h2 class="font-bold pt-4">4. Seguridad de la información</h2>
        <p>
            Implementamos medidas razonables para proteger tus datos personales contra accesos no
            autorizados, pérdida o alteración. Sin embargo, debes considerar que ningún sistema es
            completamente seguro en internet.
        </p>

        <h2 class="font-bold pt-4">5. Conservación de datos</h2>
        <p>
            Los datos se conservarán únicamente durante el tiempo necesario para cumplir con los fines
            mencionados (procesar pedidos, soporte al cliente y obligaciones legales).
        </p>

        <h2 class="font-bold pt-4">6. Derechos del usuario</h2>
        <p>
            Tienes derecho a acceder, rectificar o eliminar tu información personal, así como a
            oponerte a su uso. Para ejercer estos derechos puedes contactarnos directamente.
        </p>

        <h2 class="font-bold pt-4">7. Cookies y tecnologías similares</h2>
        <p>
            Nuestro sitio puede utilizar cookies básicas para mejorar la navegación. No utilizamos
            cookies de terceros con fines publicitarios.
        </p>

        <h2 class="font-bold pt-4">8. Modificaciones</h2>
        <p>
            Papelería Andy puede actualizar esta Política de Privacidad en cualquier momento. Los
            cambios serán publicados en este sitio web.
        </p>

        <h2 class="font-bold pt-4">9. Contacto</h2>
        <p>
            Si tienes dudas o solicitudes relacionadas con tu información personal, puedes
            comunicarte con nosotros a través de nuestro número de atención al cliente o en nuestra
            página de Facebook oficial.
        </p>

        <p class="mt-8 text-sm text-gray-500">
            Última actualización: {{ new Date().toLocaleDateString() }}
        </p>
    </div>

    <Footer/>
</template>

<script setup>
    import SeoHead from '@/components/layout/SeoHead.vue';
</script>
