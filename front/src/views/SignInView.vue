<script setup>
import { useSessionStore } from '@/stores/session.js';
import { reactive } from 'vue';
import { useRouter } from "vue-router";
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';

const router = useRouter();
const Session = useSessionStore();
var form = reactive({
  email: 'tedy-spo@mail.com',
  password: '123456789',
  errorMessage: '',
});


function sendForm() {
  Session.signIn(form).then((connection) => {
    if (connection.ok) {
      console.log('oui');
    } else {
      console.log('non');
    }
  })
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
    <Button type="submit" label="Se connecter" />
  </form>
</template>

<style lang="scss" scoped></style>
