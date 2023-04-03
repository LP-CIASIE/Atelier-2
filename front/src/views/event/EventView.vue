<script setup>
import Card from "primevue/card";
import Skeleton from "primevue/skeleton";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import ParticipantsListElement from "@/components/assets/ParticipantsListElement.vue";
import "leaflet/dist/leaflet.css";
import L from "leaflet";

import { ref, reactive, inject, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useSessionStore } from "@/stores/session.js";

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
	mainLocation: {
		content: {},
		address: "",
		addressOfApi: false,
	},
	links: [],
	comments: [],
	pending: true,
});

const modalActive = ref(false);

function getEvent() {
	return API.getActionRequest(`/events/${route.params.id}`).then((data) => {
		event.title = data.event.title;
		event.description = data.event.description;
		event.date = data.event.date;
		event.is_public = data.event.is_public;

		let links = data.event.links;
		getOwner(links.owner.href);

		// Attendre les routes API
		getParticipants(links.participants.href);
		getLocations(links.locations.href).then(() => {
			createMap();
		});
		// getLinks(links.urls.href);
		getComments(links.comments.href);

		event.pending = false;
	});
}

function getParticipants(url) {
	API.getActionRequest(url).then((data) => {
		event.participants = data.usersEvent;

		// Trier les participants par ordre de la propriété state (accepted, pending, refused) avec is_here en premier
		event.participants.sort((a, b) => {
			if (a.state == "accepted" && b.state == "accepted") {
				if (a.is_here == true && b.is_here == false) {
					return -1;
				}
				if (a.is_here == false && b.is_here == true) {
					return 1;
				}
			} else {
				if (a.state < b.state) {
					return -1;
				}
				if (a.state > b.state) {
					return 1;
				}
			}
			return 0;
		});

		// l'organisateur en premier (is_organisator)
		event.participants.sort((a, b) => {
			if (a.is_organisator == true && b.is_organisator == false) {
				return -1;
			}
			if (a.is_organisator == false && b.is_organisator == true) {
				return 1;
			}
			return 0;
		});
	});
}

function getLocations(url) {
	return API.getActionRequest(url).then((data) => {
		let mainLocation = null;

		data.locations.forEach((location) => {
			if (location.is_related == 0) {
				mainLocation = location;
			}
		});

		event.locations = data.locations;

		if (mainLocation == null) {
			event.mainLocation.address = "Aucune adresse renseignée";
			event.mainLocation.content = {};
			return;
		}

		event.mainLocation.content = mainLocation;

		return getAddress([mainLocation.long, mainLocation.lat]).then((address) => {
			event.mainLocation.address = address;
			if (address == "Adresse inconnue") {
				event.mainLocation.addressOfApi = true;
			}
		});
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

function getAddress(lonLat) {
	return API.get(`https://api-adresse.data.gouv.fr/reverse/?lon=${lonLat[0]}&lat=${lonLat[1]}`).then((response) => {
		if (response.data.features.length > 0) {
			return response.data.features[0].properties.label;
		} else {
			return "Adresse inconnue";
		}
	});
}

var mapLeaflet = null;

function createMap() {
	mapLeaflet = L.map("map", {
		zoomControl: true,
		attributionControl: false,
	}).setView([event.mainLocation.content.lat, event.mainLocation.content.long], 7);

	L.tileLayer("https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}", {
		subdomains: ["mt0", "mt1", "mt2", "mt3"],
	}).addTo(mapLeaflet);

	// Marker
	const marker = L.marker([event.mainLocation.content.lat, event.mainLocation.content.long]).addTo(mapLeaflet);
	marker.bindTooltip(`<p class='m-0'>${event.mainLocation.content.name}</p><p class='m-0'>${event.mainLocation.address}</p>`, { direction: "top", offset: [-15, -10] });
}

var listMarker = ref([]);

function showAllLocations() {
	var greenIcon = new L.Icon({
		iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png",
		shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
		iconSize: [25, 41],
		iconAnchor: [12, 41],
		popupAnchor: [1, -34],
		shadowSize: [41, 41],
	});

	event.locations.forEach((location) => {
		if (location.is_related == 1) {
			const marker = L.marker([location.lat, location.long], { icon: greenIcon }).addTo(mapLeaflet);
			marker.bindTooltip("Chargement...", { direction: "top", offset: [0, -37] });
			listMarker.value.push(marker);
			getAddress([location.long, location.lat]).then((address) => {
				marker.bindTooltip(`<p class='m-0'>${location.name}</p><p class='m-0'>${address}</p>`, { direction: "top", offset: [0, -37] });
			});
		}
	});
}

function hideAllMarker() {
	listMarker.value.forEach((marker) => {
		mapLeaflet.removeLayer(marker);
	});

	listMarker.value = [];
}

onMounted(() => {
	getEvent();
});
</script>

<template>
	<template v-if="event.pending">
		<Card>
			<template #title>
				<Skeleton type="text" width="20%" height="2rem" class="mt-2" />
				<Skeleton type="text" width="30%" height="1.5rem" class="mt-3" />
			</template>
			<template #content>
				<Skeleton type="text" width="100%" height="4rem" />
				<Skeleton type="text" width="100%" height="20rem" class="mt-3" />
			</template>
		</Card>
	</template>
	<template v-else>
		<Card class="event-view">
			<template #title>
				<h1>{{ event.title }}</h1>
				<h2>
					{{ new Date(event.date).toLocaleDateString("fr-FR", { weekday: "long", year: "numeric", month: "long", day: "numeric", hour: "numeric", minute: "numeric" }) }}
				</h2>
			</template>
			<template #content>
				<p>{{ event.description }}</p>
				<Card>
					<template #title>
						<h3>Lieu de rendez-vous</h3>
						<p>{{ event.mainLocation.content.name }}</p>
					</template>
					<template #content>
						<div id="map"></div>
						<p>{{ event.mainLocation.address }}</p>
						<template v-if="listMarker.length > 0">
							<Button label="Cacher les autres lieux" icon="pi pi-map-marker" text @click="hideAllMarker" />
						</template>
						<template v-else>
							<Button label="Voir les autres lieux" icon="pi pi-map-marker" text @click="showAllLocations" />
						</template>
					</template>
				</Card>
			</template>
		</Card>

		<Card class="mt-5">
			<template #title>
				<h3>Participants</h3>
			</template>
			<template #content>
				<Button label="Voir la listes des participants" text @click="modalActive = !modalActive" />
				<template v-if="event.participants.length > 0">
					<Dialog v-model:visible="modalActive" :modal="true" :closable="false" :dismissableMask="true" :rtl="false" :showHeader="false" :closeOnEscape="true">
						<div class="listParticipants">
							<template v-for="participant in event.participants">
								<ParticipantsListElement :participant="participant" />
							</template>
						</div>
					</Dialog>
				</template>
			</template>
		</Card>
	</template>
</template>

<style lang="scss" scoped>
#map {
	width: 100%;
	height: 20rem;
}
.p-dialog .p-dialog-content {
	padding-top: 2rem;
}
</style>
