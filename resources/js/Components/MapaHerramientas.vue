<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import iconUrl from 'leaflet/dist/images/marker-icon.png';
import iconRetinaUrl from 'leaflet/dist/images/marker-icon-2x.png';
import shadowUrl from 'leaflet/dist/images/marker-shadow.png';

const props = defineProps({
    herramientas: { type: Array, default: () => [] },
});

const contenedor = ref(null);
let mapa = null;

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({ iconUrl, iconRetinaUrl, shadowUrl });

onMounted(() => {
    const conUbicacion = props.herramientas.filter((h) => h.latitud && h.longitud);
    const centro = conUbicacion.length
        ? [Number(conUbicacion[0].latitud), Number(conUbicacion[0].longitud)]
        : [19.4326, -99.1332];

    mapa = L.map(contenedor.value).setView(centro, 13);

    // Servicio de terceros: teselas de OpenStreetMap
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; Colaboradores de OpenStreetMap',
    }).addTo(mapa);

    conUbicacion.forEach((h) => {
        L.marker([Number(h.latitud), Number(h.longitud)])
            .addTo(mapa)
            .bindPopup(`<strong>${h.nombre}</strong><br>${h.disponible ? 'Disponible' : 'Prestada'}`);
    });
});

onBeforeUnmount(() => mapa?.remove());
</script>

<template>
    <div ref="contenedor" class="h-80 w-full rounded shadow"></div>
</template>