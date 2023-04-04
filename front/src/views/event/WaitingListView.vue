<script setup>
import { ref, inject, onMounted } from "vue";
import { useSessionStore } from "@/stores/session.js";
import WaitingEventsList from "../../components/WaitingEventsList.vue";
import Message from 'primevue/message';

const API = inject("api");
const BUS = inject("bus");
const Session = useSessionStore();

const events = ref([]);
const loading = ref(true);
const message = ref('');


function getEvents() {
  API.getActionRequest("/events", {
    page: 1,
    size: 10000,
    filter: "pending",
  }).then((data) => {
    events.value = data.events;
    loading.value = false;
  });
}

function deleteEvent(id_event) {
  events.value.forEach(el => {
    if (el.id == id_event) {
      events.value.splice(events.value.indexOf(el), 1);
    }
  })
}

BUS.on('deleteEventWaitingList', (id_event) => {
  deleteEvent(id_event);
  message.value = 'Votre réponse a bien été prise en compte !';
  setTimeout(() => message.value = '', 4000);
});

onMounted(() => {
  getEvents();
});
</script>

<template>
  <template v-if="loading">
    <p>Chargement...</p>
  </template>
  <template v-else>
    <Message v-if="message !== ''" severity="success" :life="3000">{{ message }}</Message>
    <template v-if="events.length == 0">
      <p>Vous n'avez pas d'événements en attente</p>
    </template>
    <template v-else>
      <WaitingEventsList :events="events" />
    </template>
  </template>
</template>

<style lang="scss" scoped></style>