<script setup>
import { useSessionStore } from "@/stores/session.js";
import { reactive } from "vue";
import { useRouter } from "vue-router";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import ProgressSpinner from "primevue/progressspinner";

const router = useRouter();
const Session = useSessionStore();
var form = reactive({
	email: {
		content: "",
		error_message: "",
	},
	password: {
		content: "",
		error_message: "",
	},
	error_message: "",
	pending: false,
});

function on_submit() {
	var inputOK = true;
	form.error_message = "";
	form.email.error_message = "";
	form.password.error_message = "";

	const regexMail = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	if (!regexMail.test(form.email.content)) {
		form.email.error_message = "Email invalide";
		inputOK = false;
	}

	if (form.password.content.length == 0) {
		form.password.error_message = "Mot de passe non renseigné";
		inputOK = false;
	}

	if (inputOK) {
		form.pending = true;
		Session.signIn(form).then((connection) => {
			if (connection.ok) {
				router.push({ name: "home" });
			} else {
				form.pending = false;
				form.error_message = connection.message;
			}
		});
	}
}
</script>

<template>
	<form @submit.prevent="on_submit">
		<h1>Connexion</h1>

		<div>
			<span class="p-float-label">
				<InputText id="mail" v-model="form.email.content" type="text"
					:class="{ 'p-invalid': form.email.error_message || form.error_message }" aria-describedby="text-error" />
				<label for="mail">Email</label>
			</span>
			<small v-if="form.email.error_message.length > 0" class="p-error" id="text-error">{{ form.email.error_message ||
				"&nbsp;" }}</small>
		</div>

		<div>
			<span class="p-float-label">
				<InputText id="password" v-model="form.password.content" type="password"
					:class="{ 'p-invalid': form.password.error_message || form.error_message }" aria-describedby="text-error" />
				<label for="password">Mot de passe</label>
			</span>
			<small v-if="form.password.error_message.length > 0" class="p-error" id="text-error">{{ form.password.error_message
				|| "&nbsp;" }}</small>
		</div>

		<small v-if="form.error_message.length > 0" class="p-error" id="text-error">{{ form.error_message || "&nbsp;"
		}}</small>

		<div class="list-button">
			<Button type="button" label="Créer un compte" @click="router.push({ name: 'signUp' })" outlined />
			<Button type="submit" label="Se connecter" :disabled="form.pending" />
		</div>
	</form>
</template>

<style lang="scss" scoped>
@import "@/assets/scss/variables.scss";

article {
	align-items: center;
}

form {
	max-width: $max-width;
	width: 100%;
	margin: auto;
	padding-bottom: 150px;
}

input {
	width: 100%;
}

.list-button {
	display: flex;
	justify-content: space-between;
	gap: 2px 5px;
	flex-wrap: wrap;

	button {
		flex-grow: 1;
	}
}</style>
