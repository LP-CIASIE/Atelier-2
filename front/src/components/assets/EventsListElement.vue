<script setup>
// Leaflet
import "leaflet/dist/leaflet.css";
import L from "leaflet";

import Button from "primevue/button";

import { reactive, computed, onMounted } from "vue";

const props = defineProps({
	event: {
		type: Object,
		required: true,
	},
});

const map = reactive({
	zoom: 12,
	center: [48.692054, 6.184417],
});

const eventFormated = reactive({
	title: props.event.title,
	description: props.event.description,
	date: props.event.date,
});

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

var mapLeaflet = undefined;

function createMap() {
	try {
		mapLeaflet = L.map("map-" + props.event.id, {
			center: map.center,
			zoom: map.zoom,
			zoomControl: false,
			attributionControl: false,
		}).setView(map.center, map.zoom);
		mapLeaflet.dragging.disable();
		mapLeaflet.doubleClickZoom.disable();
		mapLeaflet.touchZoom.disable();
		mapLeaflet.scrollWheelZoom.disable();

		// Marker
		const marker = L.marker(map.center).addTo(mapLeaflet);
		marker.bindTooltip("Point de rendez-vous", { direction: "top", offset: [-15, -10] });

		L.tileLayer("https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}", {
			subdomains: ["mt0", "mt1", "mt2", "mt3"],
		}).addTo(mapLeaflet);
	} catch (e) {}
}

onMounted(() => {
	setTimeout(() => {
		createMap();
	}, 100);
});
</script>

<template>
	<div class="event">
		<div :id="'map-' + event.id" class="map-event"></div>
		<div class="event__description">
			<p class="title">{{ eventTitle }}</p>
			<p class="desc">{{ eventDescription }}</p>
			<div class="bottom">
				<p class="date">{{ eventDate }}</p>
				<Button class="button" @click="$router.push('/event/' + event.id)" label="Voir l'évenement" size="small" />
			</div>
		</div>
	</div>
</template>

<style lang="scss" scoped>
.event {
	height: 200px;
	width: 100%;
	display: flex;
	padding: 0.5rem;
	gap: 0.5rem;

	.map-event {
		height: 100%;
		width: 100%;
		max-width: 200px;
		border-radius: 4px;
	}

	&__description {
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		gap: 0.5rem;
		padding: 0.5rem;
		padding-top: 0;
		width: 100%;

		.bottom {
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		& > * {
			margin: 0;
		}

		.title {
			margin-top: 0.5rem;
			font-size: 1.2rem;
			font-weight: 600;
		}

		.desc {
			overflow: auto;
			flex-grow: 1;
			font-size: 0.9rem;
			opacity: 0.8;
		}

		.date {
			font-size: 0.8rem;
			opacity: 0.8;
			margin: 0;
		}
	}
}
</style>
