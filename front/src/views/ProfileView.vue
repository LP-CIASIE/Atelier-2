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
	pending: false,
	success_message: "",
});

function on_submit() {
	form.pending = true;

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

				<div>
					<span class="p-float-label">
						<InputText
							id="mail"
							v-model="form.email.content"
							type="text"
							:class="{ 'p-invalid': form.email.error_message || form.error_message }"
							aria-describedby="text-error"
						/>
						<label for="mail">Email</label>
					</span>
					<small v-if="form.email.error_message.length > 0" class="p-error" id="text-error">{{
						form.email.error_message || "&nbsp;"
					}}</small>
				</div>

				<div>
					<button
						class="p-button p-button-rounded"
						:class="{ 'p-button-primary': form.success_message == '', 'p-button-success': form.success_message }"
						:disabled="form.pending"
					>
						Edit
					</button>
				</div>
			</form>
		</template>
	</Card>
</template>

<style lang="scss" scoped></style>
