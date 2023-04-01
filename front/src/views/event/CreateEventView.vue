<script setup>
import Calendar from "primevue/calendar";
import MultiSelect from "primevue/multiselect";

import { ref, reactive, inject } from "vue";
import { useSessionStore } from "@/stores/session.js";

const Session = useSessionStore();
const API = inject("api");

var form = reactive({
	title: {
		content: "",
	},
	description: {
		content: "",
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

const selectedUsers = ref([]);

function searchUsers(event) {
	let value = event.value;
	if (value.length < 3) {
		usersFind.value = selectedUsers.value;
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
			usersFind.value = Array.from(new Set([...usersFind.value, ...selectedUsers.value]));
			usersFind.value = [...usersFind.value, ...selectedUsers.value.filter((obj2) => !usersFind.value.some((obj1) => obj1.id === obj2.id))];
			usersFindPending.value = false;
		})
		.catch((error) => {
			console.log(error);
		});
}
</script>

<template>
	<h1>Création d'un événement</h1>
	<form>
		<MultiSelect v-model="selectedUsers" :options="usersFind" filter optionLabel="name" placeholder="Invite tes amis !" class="w-full md:w-20rem" @filter="searchUsers">
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
	</form>
</template>

<style lang="scss" scoped></style>
