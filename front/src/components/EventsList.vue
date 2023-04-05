<script setup>
import EventsListElement from "@/components/assets/EventsListElement.vue";

const props = defineProps({
	events: {
		type: Array,
		default: [],
	},
	loading: {
		type: Boolean,
		default: false,
	},
});
</script>

<template>
	<div>
		<template v-if="loading">
			<template v-for="i in 5">
				<EventsListElement :skeleton="true" />
			</template>
		</template>
		<template v-else>
			<template v-for="(event, index) in events" :key="event.id">
				<EventsListElement :event="event" :ref="'event-' + event.id" />
			</template>
		</template>
	</div>
</template>

<style lang="scss" scoped>
.p-virtualscroller {
	max-height: 800px;
	height: 100%;
}
</style>
