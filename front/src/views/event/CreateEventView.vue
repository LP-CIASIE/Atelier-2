<script setup>
import Calendar from "primevue/calendar";
import MultiSelect from "primevue/multiselect";
import InputText from "primevue/inputtext";
import Button from "primevue/button";

import { ref, reactive, inject } from "vue";
import { useRouter } from "vue-router";
import { useSessionStore } from "@/stores/session.js";

const Session = useSessionStore();
const router = useRouter();
const API = inject("api");

var form = reactive({
	title: {
		content: "",
	},
	description: {
		content: "",
	},
	selectedUsers: {
		content: [],
	},
	date: {
		content: new Date(),
	},
	is_public: {
		content: false,
	},
	error_message: "",
	pending: false,
});

const usersFind = ref([]);
const usersFindPending = ref(false);

function searchUsers(event) {
	let value = event.value;
	if (value.length < 3) {
		usersFind.value = form.selectedUsers.content;
		usersFindPending.value = false;
		return;
	}

	usersFindPending.value = true;

	API.getActionRequest(`/users?email=${value}`, { email: value }).then((data) => {
		usersFind.value = data.users.map((user) => {
			return {
				name: user.email,
				id: user.id,
			};
		});
		usersFind.value = Array.from(new Set([...usersFind.value, ...form.selectedUsers.content]));
		usersFind.value = [...usersFind.value, ...form.selectedUsers.content.filter((obj2) => !usersFind.value.some((obj1) => obj1.id === obj2.id))];
		usersFind.value = usersFind.value.filter((obj1) => Session.user.id !== obj1.id);
		usersFindPending.value = false;
	});
}

function inviteUser(id_event, id_user) {
	console.log("inviteUser", id_event, id_user);

	return API.postActionRequest("/events/" + id_event + "/users/" + id_user, {}, {});
}

function createEvent() {
	console.log("createEvent");
	form.pending = true;

	let promiseAll = [];

	API.postActionRequest(
		"/events",
		{},
		{
			title: form.title.content,
			description: form.description.content,
			date: Math.floor(Date.now() / 1000),
			is_public: 0,
		}
	).then((data) => {
		form.selectedUsers.content.forEach((user) => {
			promiseAll.push(inviteUser(data.event.id, user.id));
		});

		Promise.all(promiseAll).then(() => {
			form.pending = false;
			router.push({ name: "event", params: { id: data.event.id } });
		});
	});
}
</script>

<template>
	<h1>Création d'un événement</h1>
	<form @submit.prevent="createEvent">
		<div class="group">
			<span class="p-float-label">
				<InputText id="title" v-model="form.title.content" type="text" aria-describedby="text-error" />
				<label for="title">Titre</label>
			</span>
			<span class="p-float-label">
				<InputText id="description" v-model="form.description.content" type="text" aria-describedby="text-error" />
				<label for="description">Description</label>
			</span>
		</div>
		<div class="group">
			<MultiSelect v-model="form.selectedUsers.content" :options="usersFind" filter optionLabel="name" placeholder="Invite tes amis !" @filter="searchUsers">
				<template #empty>
					<span class="p-d-block p-py-2 p-px-3">Pour commencer la recherche, il faut avoir écrit au moins 3 lettres</span>
				</template>
				<template #emptyfilter>
					<template v-if="usersFindPending">
						<span class="p-d-block p-py-2 p-px-3">Recherche en cours...</span>
					</template>
					<template v-else>
						<span class="p-d-block p-py-2 p-px-3">Aucun résultat</span>
					</template>
				</template>
			</MultiSelect>
			<Calendar id="calendar-24h" v-model="form.date.content" showTime hourFormat="24" date-format="dd/mm/yy" />
		</div>
		<div class="group">
			<Button type="button" label="Annuler" @click="router.push({ name: 'signUp' })" outlined />
			<Button type="submit" label="Créer" :disabled="form.pending" />
		</div>
	</form>
</template>

<style lang="scss" scoped>
form {
	display: flex;
	flex-direction: column;

	.group {
		display: grid;
		grid-template-columns: 1fr 1fr;
		grid-gap: 1rem;
		margin-bottom: 1rem;

		@media screen and (max-width: 768px) {
			grid-template-columns: 1fr;
		}

		& > * {
			grid-column: 1 span;
			margin: 0;

			& > input {
				width: 100%;
			}
		}

		.p-multiselect {
			width: 100%;
			max-width: 100%;
			overflow: hidden;
		}
	}

	#calendar-24h {
		width: 100%; /* Ajout de cette ligne */
	}
}
</style>
