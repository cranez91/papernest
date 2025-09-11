<template>
    <Header/>

    <div class="max-w-3xl mx-auto py-10 px-6 prose prose-slate pt-26">
        <h1 class="text-3xl font-bold mb-6">
            Términos y Condiciones
        </h1>

        <p>
            Bienvenido a <strong>Papelería Andy</strong>. Al realizar un pedido en nuestro sitio web,
            aceptas los siguientes términos y condiciones. Te pedimos leerlos con atención.
        </p>

        <h2 class="font-bold pt-4">1. Objeto</h2>
        <p>
            Estos términos regulan el uso de nuestra plataforma en línea para la compra de productos de
            papelería, así como las condiciones de entrega y pago dentro de nuestra área de servicio local.
        </p>

        <h2 class="font-bold pt-4">2. Pedidos</h2>
        <ul>
            <li>Los pedidos se realizan únicamente a través de nuestro sitio web oficial.</li>
            <li>El cliente es responsable de proporcionar información de contacto y dirección correcta.</li>
            <li>Nos reservamos el derecho de rechazar pedidos en caso de falta de stock o direcciones fuera de la zona de reparto.</li>
        </ul>

        <h2 class="font-bold pt-4">3. Entregas</h2>
        <ul>
            <li>Las entregas se realizan únicamente en las zonas locales previamente definidas.</li>
            <li>El tiempo estimado de entrega será informado al confirmar el pedido, pudiendo variar según disponibilidad.</li>
            <li>Si no se encuentra nadie en el domicilio al momento de la entrega, el pedido se cancelará y deberá volver a realizarse.</li>
        </ul>

        <h2 class="font-bold pt-4">4. Pagos</h2>
        <ul>
            <li>El único método de pago aceptado es <strong>efectivo contra entrega</strong>.</li>
            <li>El cliente debe contar con el monto exacto al momento de recibir el pedido.</li>
            <li>No se aceptan transferencias, depósitos u otros métodos de pago en esta plataforma.</li>
        </ul>

        <h2 class="font-bold pt-4">5. Precios</h2>
        <p>
            Todos los precios publicados incluyen impuestos aplicables. Los precios pueden cambiar sin
            previo aviso, pero se respetará el precio confirmado al momento de la compra.
        </p>

        <h2 class="font-bold pt-4">6. Cancelaciones</h2>
        <ul>
            <li>El cliente puede cancelar un pedido <strong>antes de que haya sido despachado</strong>.</li>
            <li>Una vez en ruta, no se aceptan cancelaciones.</li>
        </ul>

        <h2 class="font-bold pt-4">7. Responsabilidades</h2>
        <ul>
            <li>Papelería Andy no se hace responsable por retrasos ocasionados por causas externas (clima, tráfico, imprevistos).</li>
            <li>Nos comprometemos a entregar productos en buen estado y de acuerdo a lo solicitado.</li>
        </ul>

        <h2 class="font-bold pt-4">8. Modificaciones</h2>
        <p>
            Papelería Andy se reserva el derecho de modificar en cualquier momento estos Términos y
            Condiciones. Los cambios serán publicados en este sitio web.
        </p>

        <h2 class="font-bold pt-4">9. Contacto</h2>
        <p>
            Para dudas o aclaraciones sobre tu pedido, puedes contactarnos a través de nuestro número de
            atención al cliente o en nuestra página de Facebook oficial.
        </p>

        <p class="mt-8 text-sm text-gray-500">
            Última actualización: {{ new Date().toLocaleDateString() }}
        </p>
    </div>

    <Footer/>
</template>

<script setup>
</script>