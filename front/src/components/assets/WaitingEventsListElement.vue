<script setup>
import "leaflet/dist/leaflet.css";
import Message from 'primevue/message';
import Button from "primevue/button";
import Dialog from 'primevue/dialog';
import Card from "primevue/card";
import Textarea from 'primevue/textarea';
import { useSessionStore } from "@/stores/session.js";
import { ref, reactive, computed, onMounted, inject } from "vue";

const Session = useSessionStore();
const props = defineProps({
  event: {
    type: Object,
    required: true,
  },
});

const API = inject("api");
const BUS = inject("bus");

const visible = ref(false);
const comment = ref("");
const state = ref('');
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

function sendResponse() {
  API.putActionRequest("/events/" + props.event.id + "/users/" + Session.user.id, {}, {
    comment: comment.value,
    state: state.value,
  }).then((data) => {
    BUS.emit("deleteEventWaitingList", props.event.id);
  });
}
</script>

<template>
  <Card>
    <template #content>
      <div class="flex">
        <div class="event">
          <p class="title">{{ eventTitle }}</p>
          <p class="desc">{{ eventDescription }}</p>
          <div class="bottom">
            <p class="date">{{ eventDate }}</p>
          </div>
        </div>
        <div class="button">
          <Button icon="pi pi-check" aria-label="Filter"
            @click="visible = true, comment = 'Je serais bien présent à l\'événement !', state = 'accepted'" />
          <Button icon="pi pi-times" severity="danger" aria-label="Cancel"
            @click="visible = true, comment = 'Je ne serais pas présent pour l\'événement.', state = 'refused'" />
        </div>
      </div>
    </template>
  </Card>
  <Dialog v-model:visible="visible" modal header="Header" :style="{ width: '50vw' }"
    :breakpoints="{ '960px': '75vw', '641px': '100vw' }" :dismissableMask='true'>
    <Textarea v-model:value="comment" rows="5" cols="93" style="max-width: 100%; min-width: 100%;" />
    <template #footer>
      <Button label="annuler" icon="pi pi-times" @click="visible = false" />
      <Button label="envoyer" icon="pi pi-check" @click="sendResponse(), visible = false" />
    </template>
  </Dialog>
</template>

<style lang="scss">
.flex {
  display: flex;
  justify-content: space-between;
  align-items: center;

  .button {
    display: flex;
    gap: 0.5rem;
  }
}

.eventCard>.p-card {
  height: 120px;
  width: 100%;
  display: flex;
  padding: 0 0.5rem;
  margin: 1rem 0;
  gap: 0.5rem;
  color: #333;

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
    }
  }
}
</style>