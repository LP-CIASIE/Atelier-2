<script setup>
import { useSessionStore } from '@/stores/session.js';
import { reactive } from 'vue';
import { useRouter } from "vue-router";
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import ProgressSpinner from 'primevue/progressspinner';

const router = useRouter();
const Session = useSessionStore();
var form = reactive({
  email: '',
  password: '',
  errorMessage: '',
  pending: false
});


function sendForm() {
  form.pending = true;
  Session.signIn(form).then((connection) => {
    if (connection.ok) {
      router.push({ name: "home" })
    } else {
      // pending = false;
    }
  })
}

</script>

<template>
  <form @submit.prevent="sendForm">
    <h1>Connexion</h1>
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
    <p>
      <small class="p-error" id="text-error">{{ form.errorMessage || '&nbsp;' }}</small>
    </p>
    <div class="button-on-right">
      <Button v-if="form.pending" type="submit" label="Se connecter" disabled />
      <Button v-else type="submit" label="Se connecter" />
    </div>
  </form>
</template>

<style lang="scss" scoped>
form {
  max-width: $max-width;
  margin: auto;
  margin-bottom: 150px;
}

input {
  width: 100%;
}

.button-on-right {
  text-align: right;
}
</style>
