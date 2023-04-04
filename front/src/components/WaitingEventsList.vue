<script setup>
import WaitingEventsListElement from "@/components/assets/WaitingEventsListElement.vue";
import { ref, inject, onMounted } from "vue";
import { useSessionStore } from "@/stores/session.js";

const API = inject("api");
const BUS = inject("bus");
const Session = useSessionStore();

const props = defineProps({
  events: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <div>
    <template v-for="event in events">
      <WaitingEventsListElement :event="event" />
    </template>
  </div>
</template>

<style lang="scss" scoped></style>