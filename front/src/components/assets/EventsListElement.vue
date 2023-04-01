<script setup>
// Leaflet
import "leaflet/dist/leaflet.css";
import { LMap, LTileLayer, LMarker, LTooltip } from "@vue-leaflet/vue-leaflet";

import Button from "primevue/button";

import { defineProps, reactive } from "vue";

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

eventFormated.description = props.event.description.length > 400 ? eventFormated.description.substring(0, 400) + "..." : eventFormated.description;
eventFormated.date = new Date(props.event.date).toLocaleDateString("fr-FR", { weekday: "long", year: "numeric", month: "long", day: "numeric" });
</script>

<template>
	<div class="event">
		<l-map class="map-event" :options="{ dragging: false, doubleClickZoom: false, zoomControl: false, attributionControl: false }" ref="map" :zoom="map.zoom" :center="map.center">
			<l-tile-layer :subdomains="['mt0', 'mt1', 'mt2', 'mt3']" url="https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}" layer-type="base" name="Google Maps" />
			<l-marker name="Point de rendez-vous" :lat-lng="map.center" attribution="test">
				<l-tooltip :options="{ direction: 'top', offset: [-15, -10] }"><span>Point de rendez-vous</span></l-tooltip>
			</l-marker>
		</l-map>
		<div class="event__description">
			<p class="title">{{ eventFormated.title }}</p>
			<p class="desc">{{ eventFormated.description }}</p>
			<div class="bottom">
				<p class="date">{{ eventFormated.date }}</p>
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
