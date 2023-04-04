<script setup>
import EventsList from "@/components/EventsList.vue";
import Button from "primevue/button";

import { ref, inject, onMounted } from "vue";
import { useSessionStore } from "@/stores/session.js";

const API = inject("api");
const BUS = inject("bus");
const Session = useSessionStore();

const events = ref([]);
const loading = ref(true);
const waitingList = ref(0);

function getEvents() {
	API.getActionRequest("/events", {
		page: 1,
		size: 10000,
		filter: "accepted",
	}).then((data) => {
		events.value = data.events;
		loading.value = false;

		setTimeout(() => {
			BUS.emit("showEventClosestDay", "run");
		}, 300);
	});
}

function getEventsInPending() {
	API.getActionRequest("/events", {
		page: 1,
		size: 10000,
		filter: "pending",
	}).then((data) => {
		waitingList.value = data.events.length;
	});
}

onMounted(() => {
	getEvents();
	getEventsInPending();
});
</script>

<template>
	<div class="title">
		<h1>Listes des événements</h1>
		<div class="button">
			<template v-if="waitingList == 0">
				<Button :label="waitingList.toString()" @click="$router.push('/event/waiting-list')" disabled />
			</template>
			<template v-else>
				<Button :label="waitingList.toString()" @click="$router.push('/event/waiting-list')" severity="success" />
			</template>
			<Button label="Créer un événement" icon="pi pi-plus" @click="$router.push('/event/create')" />
		</div>
	</div>
	<template v-if="loading">
		<p>Chargement...</p>
	</template>
	<template v-else>
		<template v-if="events.length === 0">
			<p>Vous n'avez pas encore d'événement</p>
		</template>
		<template v-else>
			<EventsList :events="events" />
		</template>
	</template>
</template>

<style lang="scss" scoped>
.title {
	display: flex;
	justify-content: space-between;
	align-items: center;

	.button {
		display: flex;
		gap: 1rem;
	}
}
</style>
