<script setup>
import { ref, inject, onMounted } from "vue";
import { useSessionStore } from "@/stores/session.js";
import WaitingEventsList from "../../components/WaitingEventsList.vue";
import ProgressSpinner from 'primevue/progressspinner';
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
  <div class="title">
    <h1>Listes des événements en attentes</h1>

  </div>
  <template v-if="loading">
    <ProgressSpinner style="text-align: center; width: 50px; height: 50px;" />
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

<style lang="scss" scoped>
.title {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
</style>