<template>
    <div>
        <Header/>

        

        <Footer />
    </div>
</template>

<script>
    import Header from './layout/Header.vue';
    import Footer from './layout/Footer.vue';

    export default {
        name: 'Contact',
        components: {
            Header,
            Footer
        },
        mounted() {
            this.loadGoogleMaps();
        },
        methods: {
            loadGoogleMaps() {
                // Crear el script dinámicamente
                const script = document.createElement("script");
                script.src =
                    "https://maps.googleapis.com/maps/api/js?key=AIzaSyB6KUrUvGX4s2SgOhUzfrmH-v5W89UoGU8&callback=initMap";
                script.async = true;
                script.defer = true;

                // Inicializar el mapa cuando el script esté cargado
                window.initMap = () => {
                    const map = new google.maps.Map(document.getElementById("map"), {
                            center: { lat: 20.14176443300992, lng: -101.1903960116904 },
                            zoom: 18,
                            scrollwheel: false
                        }
                    );

                    // Agregar un marcador en el centro del mapa
                    const marker = new google.maps.Marker({
                        position: { lat: 20.14176443300992, lng: -101.1903960116904 }, // Misma latitud y longitud del centro
                        map: map, // Asociar el marcador al mapa
                        title: "Mi ubicación destacada", // Texto emergente cuando se pasa el cursor sobre el marcador
                    });
                };

                document.head.appendChild(script); // Agregar el script al DOM
            }
        }
    };
</script>
