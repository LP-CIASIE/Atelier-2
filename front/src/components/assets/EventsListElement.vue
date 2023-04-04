<script setup>
import Card from "primevue/card";

import { ref, computed, inject } from "vue";
import { useSessionStore } from "@/stores/session.js";

const props = defineProps({
	event: {
		type: Object,
		required: true,
	},
});

const Session = useSessionStore();
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
	API.getActionRequest(props.event.links.locations.href).then((data) => {
		let mainLocation = null;

		data.locations.forEach((location) => {
			if (location.is_related == 0) {
				mainLocation = location;
			}
		});

		if (mainLocation == null) {
			eventLocation.value = "Aucune adresse renseignée";
			return;
		}
		API.get(`https://api-adresse.data.gouv.fr/reverse/?lon=${mainLocation.long}&lat=${mainLocation.lat}`).then((response) => {
			if (response.data.features.length > 0) {
				eventLocation.value = response.data.features[0].properties.label;
			} else {
				eventLocation.value = "Adresse inconnue";
			}
		});
	});
}

getLocation();
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
