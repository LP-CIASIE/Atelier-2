<script setup>
import Card from "primevue/card";
import "leaflet/dist/leaflet.css";

import { ref, reactive, inject, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useSessionStore } from "@/stores/session.js";
import L from "leaflet";

const Session = useSessionStore();
const router = useRouter();
const route = useRoute();
const API = inject("api");

const event = reactive({
	title: "",
	description: "",
	date: "",
	is_public: "",
	owner: {},
	participants: [],
	locations: [],
	links: [],
	comments: [],
});

function getEvent() {
	return API.getActionRequest(`/events/${route.params.id}`).then((data) => {
		event.title = data.event.title;
		event.description = data.event.description;
		event.date = data.event.date;
		event.is_public = data.event.is_public;

		let links = data.event.links;
		getOwner(links.owner.href);

		// Attendre les routes API
		// getParticipants(links.participants.href);
		// getLocations(links.locations.href);
		// getLinks(links.urls.href);
		// getComments(links.comments.href);
	});
}

function getParticipants(url) {
	API.getActionRequest(url).then((data) => {
		event.participants = data.participants;
	});
}

function getLocations(url) {
	API.getActionRequest(url).then((data) => {
		event.locations = data.locations;

		let mainLocation = event.locations.reduce((acc, location) => {
			if (location.is_severated) {
				acc.push([location.latitude, location.longitude]);
				return acc;
			}
		}, []);

		if (event.locations.length > 0) {
			console.log(mainLocation);
			createMap(mainLocation[0]);
		}
	});
}

function getLinks(url) {
	API.getActionRequest(url).then((data) => {
		event.links = data.urls;
	});
}

function getComments(url) {
	API.getActionRequest(url).then((data) => {
		event.comments = data.comments;
	});
}

function getOwner(url) {
	API.getActionRequest(url).then((data) => {
		event.owner = data.user;
	});
}

function createMap() {
	let mapLeaflet = L.map("map", {
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
}

onMounted(() => {
	getEvent();
});
</script>

<template>
	<Card class="event-view">
		<template #title>
			<h1>{{ event.title }}</h1>
			<h2>
				{{ new Date(event.date).toLocaleDateString("fr-FR", { weekday: "long", year: "numeric", month: "long", day: "numeric", hour: "numeric", minute: "numeric" }) }}
			</h2>
		</template>
		<template #content>
			<div id="map"></div>
			<p>{{ event.description }}</p>
			<p>{{ event.date }}</p>
			<p>{{ event.owner.email }}</p>
		</template>
	</Card>
</template>

<style lang="scss" scoped></style>
