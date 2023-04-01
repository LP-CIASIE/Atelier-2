<script setup>
import Avatar from "primevue/avatar";
import Card from "primevue/card";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Toast from "primevue/toast";

import { reactive, inject } from "vue";
import { useSessionStore } from "@/stores/session.js";
import { useToast } from "primevue/usetoast";

const Session = useSessionStore();
const toast = useToast();
const API = inject("api");

var form = reactive({
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
	error_message: "",
	pending: false,
});

function on_submit() {
	form.pending = true;

	if (!form.email.content || !form.password.content || !form.password.confirmation || !form.firstname.content || !form.lastname.content) {
		form.error_message = "Veuillez remplir tous les champs";
		form.pending = false;
		return;
	}

	if (form.password.content != form.password.confirmation) {
		form.password.error_message = "Les mots de passe ne correspondent pas";
		form.pending = false;
		return;
	}

	Session.updateUser(form).then((result) => {
		console.log(result);
		if (result.ok) {
			toast.add({ severity: "success", summary: "Succès", detail: "Vos informations ont bien été modifié", life: 5000 });
		} else {
			form.error_message = result.message;
		}
		form.pending = false;
	});
}
</script>

<template>
	<Toast />
	<Card class="p-m-4">
		<template #content>
			<div class="p-d-flex p-jc-between">
				<div id="Bio" class="p-d-flex p-ai-center">
					<Avatar :label="Session.user.firstname.slice(0, 2).toUpperCase()" class="mr-2" size="xlarge" style="background-color: #3f51b5; color: #ffffff" />
					<div class="description">
						<p>{{ Session.user.firstname }} {{ Session.user.lastname }}</p>
						<p>{{ Session.user.email }}</p>
					</div>
				</div>
			</div>
		</template>
	</Card>
	<Card class="p-m-4 mt-4">
		<template #content>
			<!-- Change profile data : email, password, firstname/lastname-->
			<form @submit.prevent="on_submit">
				<h1>Edition profile</h1>

				<div>
					<div>
						<span class="p-float-label">
							<InputText id="mail" v-model="form.email.content" type="text" :class="{ 'p-invalid': form.email.error_message || form.error_message }" aria-describedby="text-error" />
							<label for="mail">Email</label>
						</span>
						<small v-if="form.email.error_message.length > 0" class="p-error" id="text-error">{{ form.email.error_message || "&nbsp;" }}</small>
					</div>

					<div>
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
					</div>

					<div>
						<span class="p-float-label">
							<InputText id="password" v-model="form.password.content" type="password" :class="{ 'p-invalid': form.password.error_message || form.error_message }" aria-describedby="text-error" />
							<label for="password">Nouveau mot de passe</label>
						</span>
						<small v-if="form.password.error_message.length > 0" class="p-error" id="text-error">{{ form.password.error_message || "&nbsp;" }}</small>
						<span class="p-float-label">
							<InputText id="password_confirmation" v-model="form.password.confirmation" type="password" :class="{ 'p-invalid': form.password.error_message || form.error_message }" aria-describedby="text-error" />
							<label for="password_confirmation">Confirmation du mot de passe</label>
						</span>
						<small v-if="form.password.error_message.length > 0" class="p-error" id="text-error">{{ form.password.error_message || "&nbsp;" }}</small>
					</div>
				</div>
				<small v-if="form.error_message.length > 0" class="p-error" id="text-error">{{ form.error_message || "&nbsp;" }}</small>
				<Button type="submit" class="p-button p-button-rounded" :class="{ 'p-button-primary': form.success_message == '', 'p-button-success': form.success_message }" :disabled="form.pending">Editer les propriétés du profil</Button>
			</form>
		</template>
	</Card>
</template>

<style lang="scss" scoped>
#Bio {
	display: flex;
	gap: 1rem;
	padding: 1rem 2rem;

	p {
		margin: 0;
	}

	.description {
		display: flex;
		flex-direction: column;
		justify-content: center;
		gap: 0.2rem;

		p:nth-child(2) {
			opacity: 0.9;
		}
	}
}

form {
	padding: 1rem 2rem;
	padding-top: 0;
	display: flex;
	flex-direction: column;
	& > div {
		display: flex;
		flex-direction: column;
		flex-wrap: wrap;

		& > div {
			display: flex;
			flex-direction: row;
			flex-wrap: wrap;
			gap: 0 2rem;

			& > span {
				flex-grow: 1;

				input {
					width: 100%;
				}
			}
		}
	}
	button {
		margin-top: 1rem;
		display: flex;
		justify-content: center;
	}
}
</style>
