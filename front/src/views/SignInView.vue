<script setup>
import { useSessionStore } from '@/stores/session.js';
import { reactive } from 'vue';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';

const Session = useSessionStore();
var form = reactive({
  email: '',
  password: '',
  errorMessage: '',
});


function sendForm() {
  Session.signIn(form);
}

</script>

<template>
  <form @submit.prevent="sendForm">
    <span class="p-float-label">
      <InputText id="mail" v-model="form.email" type="text" :class="{ 'p-invalid': form.errorMessage }"
        aria-describedby="text-error" />
      <label for="mail">Email</label>
    </span>
    <span class="p-float-label">
      <InputText id="password" v-model="form.password" type="password" :class="{ 'p-invalid': form.errorMessage }"
        aria-describedby="text-error" />
      <label for="password">Mot de passe</label>
    </span>
    <small class="p-error" id="text-error">{{ form.errorMessage || '&nbsp;' }}</small>
    <Button type="submit" label="S'inscrire" />
  </form>
</template>

<style lang="scss" scoped></style>
