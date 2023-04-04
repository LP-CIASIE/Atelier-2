<script setup>
import Card from "primevue/card";
import Divider from "primevue/divider";
import Skeleton from "primevue/skeleton";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import MultiSelect from "primevue/multiselect";
import AutoComplete from "primevue/autocomplete";
import InputText from "primevue/inputtext";
import InlineMessage from "primevue/inlinemessage";

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
	id: "",
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
	urls: [],
	links: {},
	comments: [],
	pending: true,
});

const modalActive = ref(false);

// =========================================
// 	  Importation donnée pour l'affichage
// =========================================
function getEvent() {
	return API.getActionRequest(`/events/${route.params.id}`).then((data) => {
		event.id = data.event.id;
		event.title = data.event.title;
		event.description = data.event.description;
		event.date = data.event.date;
		event.is_public = data.event.is_public;

		event.links = data.event.links;

		let promises = [];

		promises.push(getOwner(event.links.owner.href));

		// Attendre les routes API
		promises.push(getParticipants(event.links.participants.href));
		promises.push(getLocations(event.links.locations.href));
		// getLinks(event.links.urls.href);
		promises.push(getComments(event.links.comments.href));

		Promise.all(promises).then(() => {
			event.pending = false;
			if (event.locations.length > 0) {
				setTimeout(createMap, 400);
			}
		});
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

		return getAddressFromLonLat([mainLocation.long, mainLocation.lat]).then((address) => {
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

function getAddressFromLonLat(lonLat) {
	return API.get(`https://api-adresse.data.gouv.fr/reverse/?lon=${lonLat[0]}&lat=${lonLat[1]}`).then((response) => {
		if (response.data.features.length > 0) {
			return response.data.features[0].properties.label;
		} else {
			return "Adresse inconnue";
		}
	});
}

function getLonLatFromAddress(address) {
	return API.get(`https://api-adresse.data.gouv.fr/search/?q=${address}`).then((response) => {
		if (response.data.features.length > 0) {
			console.log("==================================");
			console.log(response.data.features[0].geometry.coordinates);
			return response.data.features[0].geometry.coordinates;
		} else {
			return null;
		}
	});
}

// =========================================
// 	 Création de la carte pour l'événement
// =========================================
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

var greenIcon = new L.Icon({
	iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png",
	shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
	iconSize: [25, 41],
	iconAnchor: [12, 41],
	popupAnchor: [1, -34],
	shadowSize: [41, 41],
});

function showAllLocations() {
	event.locations.forEach((location) => {
		if (location.is_related == 1) {
			const marker = L.marker([location.lat, location.long], { icon: greenIcon }).addTo(mapLeaflet);
			marker.bindTooltip("Chargement...", { direction: "top", offset: [0, -37] });
			listMarker.value.push(marker);
			getAddressFromLonLat([location.long, location.lat]).then((address) => {
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

// =========================================
// Création de la carte pour l'ajout de location
// =========================================
const formCreateLocation = reactive({
	name: "",
	address: "",
	autocompleteAddress: [],
	pending: false,
	messageError: "",
	modal: false,
	marker: [],
});

var mapForm = null;

function toggleModalCreateLocation() {
	formCreateLocation.modal = true;
	setTimeout(() => {
		createMapForm();
	}, 500);
}

function createMapForm() {
	mapForm = L.map("mapForm", {
		zoomControl: true,
		attributionControl: false,
	}).setView([48.85809, 2.431057], 8);
	mapForm.doubleClickZoom.disable();

	L.tileLayer("https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}", {
		subdomains: ["mt0", "mt1", "mt2", "mt3"],
	}).addTo(mapForm);

	mapForm.on("click", function (e) {
		if (formCreateLocation.marker.length >= 5) {
			formCreateLocation.messageError = "Vous ne pouvez pas ajouter plus de 5 lieux";
			return;
		}

		let marker = null;
		if (formCreateLocation.marker.length > 0) {
			marker = L.marker([e.latlng.lat, e.latlng.lng], { icon: greenIcon }).addTo(mapForm);
		} else {
			marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(mapForm);
		}
		marker.bindTooltip("Chargement...", { direction: "top", offset: [-15, -10] });
		getAddressFromLonLat([e.latlng.lng, e.latlng.lat]).then((address) => {
			marker.bindTooltip(`<p class='m-0'>${address}</p>`, { direction: "top", offset: [-15, -10] });
			marker.address = address;
			marker.name = formCreateLocation.name ? formCreateLocation.name : "Sans nom";
		});

		formCreateLocation.marker.push(marker);

		console.log(formCreateLocation.marker);
		marker.on("click", function (e) {
			if (formCreateLocation.marker.indexOf(marker) == 0 && formCreateLocation.marker.length > 1) {
				let newMainLocation = formCreateLocation.marker[1];
				newMainLocation.setIcon(new L.Icon.Default());
			}
			mapForm.removeLayer(marker);
			formCreateLocation.marker.splice(formCreateLocation.marker.indexOf(marker), 1);
			formCreateLocation.messageError = "";
		});
	});
}

function addMarkerFromAddressMap() {
	if (formCreateLocation.marker.length >= 5) {
		formCreateLocation.messageError = "Vous ne pouvez pas ajouter plus de 5 lieux";
		return;
	}

	getLonLatFromAddress(formCreateLocation.address).then((lonlat) => {
		if (lonlat == null) {
			formCreateLocation.messageError = "L'adresse n'a pas été trouvée";
			return;
		}
		let marker = null;
		if (formCreateLocation.marker.length > 0) {
			marker = L.marker([lonlat[1], lonlat[0]], { icon: greenIcon }).addTo(mapForm);
		} else {
			marker = L.marker([lonlat[1], lonlat[0]]).addTo(mapForm);
		}
		marker.bindTooltip("Chargement...", { direction: "top", offset: [-15, -10] });
		getAddressFromLonLat([lonlat[0], lonlat[1]]).then((address) => {
			marker.address = address;
			marker.name = formCreateLocation.name ? formCreateLocation.name : "Sans nom";
			if (marker.name != "" && marker.name != null) {
				marker.bindTooltip(`<p class='m-0'>${marker.name}</p><p class='m-0'>${marker.address}</p>`, { direction: "top", offset: [-15, -10] });
			} else {
				marker.bindTooltip(`<p class='m-0'>${marker.address}</p>`, { direction: "top", offset: [-15, -10] });
			}

			formCreateLocation.address = "";
			formCreateLocation.name = "";
		});

		formCreateLocation.marker.push(marker);

		console.log(formCreateLocation.marker);
		marker.on("click", function (e) {
			if (formCreateLocation.marker.indexOf(marker) == 0 && formCreateLocation.marker.length > 1) {
				let newMainLocation = formCreateLocation.marker[1];
				newMainLocation.setIcon(new L.Icon.Default());
			}
			mapForm.removeLayer(marker);
			formCreateLocation.marker.splice(formCreateLocation.marker.indexOf(marker), 1);
			formCreateLocation.messageError = "";
		});
	});
}

function getAutoCompleteLocation() {
	API.get(`https://api-adresse.data.gouv.fr/search/?q=${formCreateLocation.address}&limit=3`).then((response) => {
		if (response.data.features.length >= 0) {
			let addresses = response.data.features.map((feature) => {
				return feature.properties.label;
			});
			formCreateLocation.autocompleteAddress = addresses;
		} else {
			formCreateLocation.autocompleteAddress = [];
		}
		formCreateLocation.latlng = [];
	});
}

function postLocations() {
	formCreateLocation.pending = true;
	let locations = formCreateLocation.marker.map((marker) => {
		let latLng = marker.getLatLng();
		return {
			name: marker.name,
			lat: latLng.lat,
			long: latLng.lng,
			is_related: marker == formCreateLocation.marker[0] ? 0 : 1,
		};
	});

	console.log("=============================");
	console.log(locations);

	let promises = [];
	locations.map((location) => {
		promises.push(API.postActionRequest(`/events/${event.id}/locations`, {}, location));
	});

	Promise.all(promises)
		.then((data) => {
			getLocations(event.links.locations.href);
			formCreateLocation.pending = false;
			formCreateLocation.modal = false;
			createMap();
		})
		.catch((error) => {
			formCreateLocation.pending = false;
			formCreateLocation.messageError = "Une erreur est survenue";
			console.log(error);
		});
}

// =========================================
// Création du form pour l'invitation d'utilisation
// =========================================
const formInviteUsers = reactive({
	selectedUsers: [],
	pending: false,
	messageError: "",
});

const usersFind = reactive({
	list: [],
	pending: false,
});

function searchUsers(event) {
	let value = event.value;
	if (value.length < 3) {
		usersFind.list = formInviteUsers.selectedUsers;
		usersFind.pending = false;
		return;
	}

	usersFind.pending = true;

	API.getActionRequest(`/users?email=${value}`, { email: value }).then((data) => {
		usersFind.list = data.users.map((user) => {
			return {
				name: user.email,
				id: user.id,
			};
		});
		usersFind.list = Array.from(new Set([...usersFind.list, ...formInviteUsers.selectedUsers]));
		usersFind.list = [...usersFind.list, ...formInviteUsers.selectedUsers.filter((obj2) => !usersFind.list.some((obj1) => obj1.id === obj2.id))];
		usersFind.list = usersFind.list.filter((obj1) => Session.user.id !== obj1.id);
		usersFind.pending = false;
	});
}

function inviteUser(id_event, id_user) {
	console.log("inviteUser", id_event, id_user);

	return API.postActionRequest("/events/" + id_event + "/users/" + id_user, {}, {});
}

function inviteUsers() {
	formInviteUsers.pending = true;
	formInviteUsers.messageError = "";

	let promises = [];

	formInviteUsers.selectedUsers.forEach((user) => {
		promises.push(inviteUser(event.id, user.id));
	});

	Promise.all(promises)
		.then((data) => {
			formInviteUsers.pending = false;
			formInviteUsers.selectedUsers = [];
			usersFind.list = [];
			getParticipants(event.links.participants.href);
		})
		.catch((error) => {
			formInviteUsers.pending = false;
			formInviteUsers.messageError = error.response.data.message;
		});
}

// =========================================
// 	  Lancement de la récupération des données
// =========================================
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
				<Skeleton type="text" width="100%" height="30rem" class="mt-3" />
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
				<Divider />
			</template>
			<template #content>
				<p>{{ event.description }}</p>
				<Card>
					<template #title>
						<h3>Lieu de rendez-vous</h3>
						<p>{{ event.mainLocation.content.name }}</p>
					</template>
					<template #content>
						<template v-if="event.locations.length > 0">
							<div id="map"></div>
							<p>{{ event.mainLocation.address }}</p>
							<template v-if="listMarker.length > 0">
								<Button label="Cacher les autres lieux" icon="pi pi-map-marker" text @click="hideAllMarker" />
							</template>
							<template v-else>
								<Button label="Voir les autres lieux" icon="pi pi-map-marker" text @click="showAllLocations" />
							</template>
						</template>
						<template v-else>
							<p>Aucun lieu n'a été ajouté pour l'instant.</p>
							<Button label="Ajouter un lieu de rendez-vous" text @click="toggleModalCreateLocation" v-if="event.owner.id == Session.user.id" />
							<Dialog v-model:visible="formCreateLocation.modal" :modal="true" :closable="false" :dismissableMask="true" :rtl="false" :showHeader="false" :closeOnEscape="true" v-if="event.owner.id == Session.user.id">
								<form @submit.prevent="addLocation" id="addLocation">
									<InlineMessage v-if="formCreateLocation.messageError" severity="error">{{ formCreateLocation.messageError }}</InlineMessage>
									<div id="mapForm"></div>
									<div class="p-inputgroup flex-1">
										<AutoComplete class="w-full" placeholder="Lieu du rendez-vous (ex: 19 Rue Murillo 75008 Paris)" v-model="formCreateLocation.address" :suggestions="formCreateLocation.autocompleteAddress" :completeOnFocus="false" :minLength="3" :delay="100" @complete="getAutoCompleteLocation" />
										<Button icon="pi pi-plus" class="p-inputgroup-addon" @click="addMarkerFromAddressMap" :disabled="formCreateLocation.address == ''" />
									</div>
									<InputText v-model="formCreateLocation.name" placeholder="Nom du lieu (ex: Maison, Travail...)" />
									<Button type="submit" label="Ajouter ce lieu" :disabled="formCreateLocation.marker.length == 0 || formCreateLocation.pending" @click="postLocations" />
								</form>
							</Dialog>
						</template>
					</template>
				</Card>
			</template>
		</Card>
	</template>
	<Card class="mt-5">
		<template #title>
			<h3>Participants</h3>
		</template>
		<template #content>
			<Button label="Voir la listes des participants" text @click="modalActive = !modalActive" />
			<template v-if="event.participants.length > 0">
				<Dialog v-model:visible="modalActive" :modal="true" :closable="false" :dismissableMask="true" :rtl="false" :showHeader="false" :closeOnEscape="true">
					<form @submit.prevent="inviteUsers" id="inviteUsers" v-if="event.owner.id == Session.user.id">
						<MultiSelect v-model="formInviteUsers.selectedUsers" :options="usersFind.list" filter optionLabel="name" placeholder="Invite tes amis !" @filter="searchUsers">
							<template #empty>
								<span class="p-d-block p-py-2 p-px-3">Ecrivez minimum 3 lettres.</span>
							</template>
							<template #emptyfilter>
								<template v-if="usersFind.pending">
									<span class="p-d-block p-py-2 p-px-3">Recherche en cours...</span>
								</template>
								<template v-else>
									<span class="p-d-block p-py-2 p-px-3">Aucun résultat.</span>
								</template>
							</template>
						</MultiSelect>
						<Button type="submit" label="Inviter" :disabled="formInviteUsers.selectedUsers.length === 0" />
					</form>
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

<style lang="scss">
#map {
	width: 100%;
	height: 20rem;
}
.p-dialog .p-dialog-content {
	padding-top: 2rem;
}

form#inviteUsers {
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	gap: 0.5rem;

	.p-multiselect {
		width: 100%;
		max-width: 350px;
	}

	button {
		width: 100%;
	}
}

.event-view {
	h1 {
		font-size: 2rem;
		margin: 0;
	}
	h2 {
		font-size: 1rem;
	}

	.p-card-content {
		padding: 0;
	}
}

form#addLocation {
	min-width: 500px;
	width: 100%;
	max-width: 500px;
	#mapForm {
		width: 100%;
		height: 20rem;
	}

	.p-inline-message,
	input {
		width: 100%;
	}

	.p-inline-message {
		margin-bottom: 0.5rem;
	}

	input {
		margin-top: 0.5rem;
	}

	.p-autocomplete {
		margin-top: 0.5rem;
	}
	button {
		margin-top: 1rem;
	}
}
</style>
