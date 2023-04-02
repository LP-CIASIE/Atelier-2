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

	API.get("/users", {
		params: {
			email: value,
		},
		headers: {
			Authorization: "Bearer " + Session.user.access_token,
		},
	})
		.then((response) => {
			let data = response.data;

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
		})
		.catch((error) => {
			console.log(error);
		});
}

function inviteUser(id_event, id_user) {
	console.log("inviteUser", id_event, id_user);
	return API.post("/events/" + id_event + "/users/" + id_user, {
		headers: {
			Authorization: "Bearer " + Session.user.access_token,
		},
	})
		.then((response) => {
			let data = response.data;
			console.log(data);
		})
		.catch((error) => {
			console.log(error);
		});
}

function createEvent() {
	console.log("createEvent");
	form.pending = true;

	let promiseAll = [];
	API.post(
		"/events",
		{
			title: form.title.content,
			description: form.description.content,
			date: Math.floor(Date.now() / 1000),
			is_public: 0,
		},
		{
			headers: {
				Authorization: "Bearer " + Session.user.access_token,
			},
		}
	)
		.then((response) => {
			let data = response.data;
			console.log(data);
			form.selectedUsers.content.forEach((user) => {
				promiseAll.push(inviteUser(data.event.id, user.id));
			});

			Promise.all(promiseAll).then(() => {
				form.pending = false;
				router.push({ name: "event", params: { id: data.event.id } });
			});
		})
		.catch((error) => {
			console.log(error);
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
			<MultiSelect v-model="form.selectedUsers.content" :options="usersFind" filter optionLabel="name" placeholder="Invite tes amis !" class="w-full md:w-20rem" @filter="searchUsers">
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
		<div class="list-button">
			<Button type="button" label="Annuler" @click="router.push({ name: 'signUp' })" outlined />
			<Button type="submit" label="Créer" :disabled="form.pending" />
		</div>
	</form>
</template>

<style lang="scss" scoped>
form {
	display: flex;
	gap: 1rem;
	flex-direction: column;

	.group {
		display: flex;

		flex-wrap: wrap;
		gap: 1rem;

		& > * {
			flex-grow: 1;
			display: flex;
			margin: 0;
			input {
				flex-grow: 1;
			}
		}
	}
	.list-button {
		display: flex;
		justify-content: space-between;
		gap: 0.5rem 1rem;
		flex-wrap: wrap;

		button {
			flex-grow: 1;
		}
	}
}
</style>
