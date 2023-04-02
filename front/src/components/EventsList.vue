<script setup>
import VirtualScroller from "primevue/virtualscroller";
import EventsListElement from "@/components/assets/EventsListElement.vue";

import { ref, inject } from "vue";

const props = defineProps({
	events: {
		type: Array,
		required: true,
	},
});

function findClosestDateIndex(targetDate) {
	// Date du jour
	let targetDateFormat = new Date(targetDate);
	let closestDateIndex = 0;
	let minDifference = Math.abs(targetDateFormat - new Date(props.events[0].date));

	for (let i = 1; i < props.events.length; i++) {
		const currentDate = props.events[i].date;
		const currentDifference = Math.abs(targetDateFormat - new Date(currentDate));

		if (currentDifference < minDifference) {
			closestDateIndex = i;
			minDifference = currentDifference;
		}
	}

	return closestDateIndex;
}

function scrollToItem(targetDate) {
	let closestDateIndex = findClosestDateIndex(targetDate);
	scroller.value.scrollToIndex(closestDateIndex);
}

function scrollToToday() {
	scrollToItem(new Date());
}

const scroller = ref(null);

// Run ScrollToToday after props are loaded
const BUS = inject("bus");
BUS.on("showEventClosestDay", () => {
	scrollToToday();
});
</script>

<template>
	<VirtualScroller ref="scroller" :items="events" :itemSize="200" :delay="100">
		<template v-slot:item="{ item, options }">
			<EventsListElement :event="item" :ref="'event-' + item.id" :key="item.id" />
		</template>
	</VirtualScroller>
</template>

<style lang="scss" scoped>
.p-virtualscroller {
	max-height: 800px;
	height: 100%;
}
</style>
