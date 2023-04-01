<script setup>
import EventsList from "@/components/EventsList.vue";
import Button from "primevue/button";

import { ref, inject, onMounted } from "vue";
import { useSessionStore } from "@/stores/session.js";

const API = inject("api");
const Session = useSessionStore();

const events = ref([]);
const loading = ref(true);

function getEvents() {
	API.get("/events", {
		params: {
			page: "1",
			size: "10000",
		},
		headers: {
			Authorization: "Bearer " + Session.user.access_token,
		},
	})
		.then((result) => {
			events.value = result.data.events;
			loading.value = false;
		})
		.catch((error) => {
			console.log(error);
		});
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
}
</style>
