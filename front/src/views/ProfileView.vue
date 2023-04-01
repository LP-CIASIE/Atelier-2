<script setup>
import Avatar from "primevue/avatar";
import Card from "primevue/card";
import InputText from "primevue/inputtext";
import Button from "primevue/button";

import { ref, inject } from "vue";
import { useSessionStore } from "@/stores/session.js";

const Session = useSessionStore();
const API = inject("api");

var form = ref({
	email: {
		content: Session.user.email,
		error_message: "",
	},
	firstname: {
		content: Session.user.firstname,
		error_message: "",
	},
	lastname: {
		content: Session.user.lastname,
		error_message: "",
	},
	password: {
		content: "",
		confirmation: "",
		error_message: "",
	},
	pending: false,
	success_message: "",
});

function on_submit() {
	form.pending = true;

	if (!form.email.content || !form.password.content || !form.firstname.content || !form.lastname.content) {
		form.password.error_message = "Veuillez remplir tous les champs";
		form.pending = false;
		return;
	}

	if (form.password.content != form.password.confirmation) {
		form.password.error_message = "Les mots de passe ne correspondent pas";
		form.pending = false;
		return;
	}

	Session.updateUser(form)
		.then((result) => {
			if (result.ok) {
			}
			form.pending = false;
		})
		.catch((error) => {
			form.pending = false;
			form.error_message = error.message;
		});
}
</script>

<template>
	<Card class="p-m-4">
		<template #content>
			<div class="p-d-flex p-jc-between">
				<div class="p-d-flex p-ai-center">
					<div>
						<Avatar label="P" class="mr-2" size="xlarge" />
						<p>Manage your account settings and set e-mail preferences.</p>
					</div>
				</div>
			</div>
		</template>
	</Card>
	<Card class="p-m-4 mt-3">
		<template #content>
			<!-- Change profile data : email, password, firstname/lastname-->
			<form @submit.prevent="on_submit">
				<h2>Edition profile</h2>

				<span class="p-float-label">
					<InputText id="mail" v-model="form.email.content" type="text" :class="{ 'p-invalid': form.email.error_message || form.error_message }" aria-describedby="text-error" />
					<label for="mail">Email</label>
				</span>
				<small v-if="form.email.error_message.length > 0" class="p-error" id="text-error">{{ form.email.error_message || "&nbsp;" }}</small>

				<span class="p-float-label">
					<InputText id="firstname" v-model="form.firstname.content" type="text" :class="{ 'p-invalid': form.firstname.error_message || form.error_message }" aria-describedby="text-error" />
					<label for="firstname">Prénom</label>
				</span>
				<small v-if="form.firstname.error_message.length > 0" class="p-error" id="text-error">{{ form.firstname.error_message || "&nbsp;" }}</small>

				<span class="p-float-label">
					<InputText id="lastname" v-model="form.lastname.content" type="text" :class="{ 'p-invalid': form.lastname.error_message || form.error_message }" aria-describedby="text-error" />
					<label for="lastname">Nom</label>
				</span>
				<small v-if="form.lastname.error_message.length > 0" class="p-error" id="text-error">{{ form.lastname.error_message || "&nbsp;" }}</small>

				<span class="p-float-label">
					<InputText id="password" v-model="form.password.content" type="password" :class="{ 'p-invalid': form.password.error_message || form.error_message }" aria-describedby="text-error" />
					<label for="password">Mot de passe</label>
				</span>
				<small v-if="form.password.error_message.length > 0" class="p-error" id="text-error">{{ form.password.error_message || "&nbsp;" }}</small>

				<span class="p-float-label">
					<InputText id="password_confirmation" v-model="form.password.confirmation" type="password" :class="{ 'p-invalid': form.password.error_message || form.error_message }" aria-describedby="text-error" />
					<label for="password_confirmation">Confirmation du Mot de passe</label>
				</span>
				<small v-if="form.password.error_message.length > 0" class="p-error" id="text-error">{{ form.password.error_message || "&nbsp;" }}</small>

				<Button class="p-button p-button-rounded" :class="{ 'p-button-primary': form.success_message == '', 'p-button-success': form.success_message }" :disabled="form.pending">Edit</Button>
			</form>
		</template>
	</Card>
</template>

<style lang="scss" scoped>
form {
	display: flex;
	flex-direction: column;

	button {
		width: 200px;
		text-align: center;
	}
}
</style>
