<script setup>
import { useSessionStore } from '@/stores/session.js';
import { inject, reactive } from 'vue';
import { useRouter } from "vue-router";
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import ProgressSpinner from 'primevue/progressspinner';

const API = inject("api");
const router = useRouter();
const Session = useSessionStore();
var form = reactive({
  email: {
    content: '',
    error_message: '',
  },
  lastname: {
    content: '',
  },
  firstname: {
    content: '',
    error_message: '',
  },
  password: {
    content: '',
    confirmation: '',
    error_message: '',
  },
  error_message: '',
  pending: false
});


function on_submit() {
  var inputOK = true;
  form.error_message = '';
  form.email.error_message = '';
  form.password.error_message = '';
  form.firstname.error_message = '';

  const regexMail = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
  if (!regexMail.test(form.email.content)) {
    form.email.error_message = 'Email invalide';
    inputOK = false;
  }

  if (form.email.content.length == 0) {
    form.email.error_message = 'Email non renseignée';
    inputOK = false;
  }

  if (form.firstname.content.length < 2) {
    form.firstname.error_message = 'Prénom trop court';
    inputOK = false;
  }

  if (form.firstname.content.length == 0) {
    form.firstname.error_message = 'Prénom non renseigné';
    inputOK = false;
  }

  if (form.password.content != form.password.confirmation) {
    form.password.error_message = 'Mots de passe non identiques';
    inputOK = false;
  }

  if (form.password.content.length < 8) {
    form.password.error_message = 'Mot de passe trop court';
    inputOK = false;
  }

  if (form.password.content.length == 0) {
    form.password.error_message = 'Mot de passe non renseigné';
    inputOK = false;
  }

  if (inputOK) {
    form.pending = true;

    var body = {
      email: form.email.content,
      password: form.password.content,
      firstname: form.firstname.content,
      role: 'user',
    }

    if (form.lastname.content.length > 0) {
      body.lastname = form.lastname.content;
    }

    API.post("/signup", body)
      .then((response) => {
        router.push({ name: "signIn" })
      })
      .catch((error) => {
        form.error_message = error.response.data.message;
        form.pending = false;
      });
  }
}

</script>

<template>
  <form @submit.prevent="on_submit">
    <h1>Inscription</h1>

    <div>
      <span class="p-float-label">
        <InputText id="mail" v-model="form.email.content" type="text" :class="{ 'p-invalid': form.email.error_message }"
          aria-describedby="text-error" />
        <label for="mail">Email</label>
      </span>
      <small v-if="form.email.error_message.length > 0" class="p-error"
        id="text-error">{{ form.email.error_message || '&nbsp;' }}</small>
    </div>

    <div>
      <span class="p-float-label">
        <InputText id="mail" v-model="form.firstname.content" type="text"
          :class="{ 'p-invalid': form.firstname.error_message }" aria-describedby="text-error" />
        <label for="mail">Prénom</label>
      </span>
      <small v-if="form.firstname.error_message.length > 0" class="p-error"
        id="text-error">{{ form.firstname.error_message || '&nbsp;' }}</small>
    </div>

    <div>
      <span class="p-float-label">
        <InputText id="lastname" v-model="form.lastname.content" type="text" aria-describedby="text-error" />
        <label for="lastname">Nom de famille</label>
      </span>
    </div>

    <div>
      <span class="p-float-label">
        <InputText id="password" v-model="form.password.content" type="password"
          :class="{ 'p-invalid': form.password.error_message }" aria-describedby="text-error" />
        <label for="password">Mot de passe</label>
      </span>
      <span class="p-float-label">
        <InputText id="confirmPassword" v-model="form.password.confirmation" type="password"
          :class="{ 'p-invalid': form.password.error_message }" aria-describedby="text-error" />
        <label for="confirmPassword">Confirmez votre mot de passe</label>
      </span>
      <small v-if="form.password.error_message.length > 0" class="p-error"
        id="text-error">{{ form.password.error_message || '&nbsp;' }}</small>
    </div>


    <small v-if="form.error_message.length > 0" class="p-error"
      id="text-error">{{ form.error_message || '&nbsp;' }}</small>

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
  margin: 1.25em 0;
}
</style>
