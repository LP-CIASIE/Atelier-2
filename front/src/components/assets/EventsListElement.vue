<script setup>
// Leaflet
import "leaflet/dist/leaflet.css";
import L from "leaflet";

import Card from "primevue/card";

import { ref, reactive, computed, onMounted, inject } from "vue";

const props = defineProps({
	event: {
		type: Object,
		required: true,
	},
});

const API = inject("api");

const eventTitle = computed(() => props.event.title);
const eventDescription = computed(() => {
	return props.event.description.length > 400 ? props.event.description.substring(0, 400) + "..." : props.event.description;
});
const eventDate = computed(() => {
	return new Date(props.event.date).toLocaleDateString("fr-FR", {
		weekday: "long",
		year: "numeric",
		month: "long",
		day: "numeric",
		hour: "numeric",
		minute: "numeric",
	});
});

const eventLocation = ref("");

function getLocation() {
	API.get(`https://api-adresse.data.gouv.fr/reverse/?lon=6.184417&lat=48.692054`).then((response) => {
		eventLocation.value = response.data.features[0].properties.label;
	});
}

getLocation();

// const map = reactive({
// 	zoom: 12,
// 	center: [48.692054, 6.184417],
// });

// var mapLeaflet = undefined;

// function createMap() {
// 	try {
// 		mapLeaflet = L.map("map-" + props.event.id, {
// 			center: map.center,
// 			zoom: map.zoom,
// 			zoomControl: false,
// 			attributionControl: false,
// 		}).setView(map.center, map.zoom);
// 		mapLeaflet.dragging.disable();
// 		mapLeaflet.doubleClickZoom.disable();
// 		mapLeaflet.touchZoom.disable();
// 		mapLeaflet.scrollWheelZoom.disable();

// 		// Marker
// 		const marker = L.marker(map.center).addTo(mapLeaflet);
// 		marker.bindTooltip("Point de rendez-vous", { direction: "top", offset: [-15, -10] });

// 		L.tileLayer("https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}", {
// 			subdomains: ["mt0", "mt1", "mt2", "mt3"],
// 		}).addTo(mapLeaflet);
// 	} catch (e) {}
// }

// onMounted(() => {
// 	setTimeout(() => {
// 		createMap();
// 	}, 100);
// });
</script>

<template>
	<RouterLink :to="{ name: 'event', params: { id: event.id } }" class="eventCard">
		<Card>
			<template #content>
				<p class="title">{{ eventTitle }}</p>
				<p class="desc">{{ eventDescription }}</p>
				<div class="bottom">
					<p class="date">{{ eventDate }}</p>
					<p class="location">{{ eventLocation }}</p>
				</div>
			</template>
		</Card>
	</RouterLink>
</template>

<style lang="scss">
.eventCard>.p-card {
	height: 120px;
	width: 100%;
	display: flex;
	padding: 0 0.5rem;
	margin: 1rem 0;
	gap: 0.5rem;
	color: #333;

	.map-event {
		height: 100%;
		width: 100%;
		max-width: 200px;
		border-radius: 4px;
	}

	.p-card-body {
		width: 100%;

		.p-card-content {
			display: flex;
			flex-direction: column;
			gap: 0.5rem;
			padding: 0;
			width: 100%;
			height: 100%;

			.bottom {
				display: flex;
				justify-content: space-between;
				align-items: center;
			}

			&>* {
				margin: 0;
			}

			.title {
				font-size: 1.2rem;
				font-weight: 600;
			}

			.desc {
				overflow: auto;
				flex-grow: 1;
				font-size: 0.9rem;
				opacity: 0.8;
			}

			.date,
			.location {
				font-size: 0.8rem;
				opacity: 0.8;
				margin: 0;
			}
		}
	}
}
</style>
