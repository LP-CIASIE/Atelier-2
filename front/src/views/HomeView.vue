<script setup>
import EventsList from "@/components/EventsList.vue";
import Button from "primevue/button";

import { ref, inject, onMounted } from "vue";
import { useSessionStore } from "@/stores/session.js";

const API = inject("api");
const Session = useSessionStore();

const events = ref();
const eventsFiltered = ref([]);

function getEvents() {
	// Temporaire pour ne pas avoir à faire des requêtes à l'API
	fetch("/tempEvents.json")
		.then((response) => {
			return response.json();
		})
		.then((data) => {
			console.log(data.events);
			events.value = data.events;
			eventsFiltered.value = events.value;
		});

	// API.get("/events", {
	// 	params: {
	// 		page: "1",
	// 		size: "10000",
	// 	},
	// 	headers: {
	// 		Authorization: "Bearer " + Session.user.access_token,
	// 	},
	// })
	// 	.then((result) => {
	// 		events.value = result.data.events;
	// 		eventsFiltered.value = events.value;

	// 		console.log(events.value);
	// 	})
	// 	.catch((error) => {
	// 		console.log(error);
	// 	});
}

onMounted(() => {
	getEvents();
});
</script>

<template>
	<div class="title">
		<h1>Listes des événements</h1>
		<Button label="Créer un événement" icon="pi pi-plus" @click="$router.push('/event/create')" />
	</div>
	<EventsList :events="eventsFiltered" />
</template>

<style lang="scss" scoped>
.title {
	display: flex;
	justify-content: space-between;
	align-items: center;
}
</style>
