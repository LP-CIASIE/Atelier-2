import { defineStore } from "pinia";
import { inject, reactive } from "vue";

export const useSessionStore = defineStore(
	"session",
	() => {
		const API = inject("api");
		const user = reactive({
			id: "",
			email: "",
			firstname: "",
			lastname: "",
			access_token: "",
			refresh_token: "",
		});

		async function signIn(form) {
			return API.post("/signin", {
				email: form.email.content,
				password: form.password.content,
			})
				.then(async (response) => {
					user.id = response.data.id_user;
					user.access_token = response.data["access-token"];
					user.refresh_token = response.data["refresh-token"];
					await getUser();
					return {
						ok: true,
					};
				})
				.catch((error) => {
					return {
						ok: false,
						message: error.response.data.message,
					};
				});
		}

		async function getUser() {
			return API.get(`/users/${user.id}`, {
				headers: {
					Authorization: `Bearer ${user.access_token}`,
				},
			})
				.then((response) => {
					user.email = response.data.user.email;
					user.firstname = response.data.user.firstname;
					user.lastname = response.data.user.lastname || "";
					return {
						ok: true,
					};
				})
				.catch((error) => {
					return {
						ok: false,
						message: error.response.data.message,
					};
				});
		}

		async function updateUser(form) {
			return API.put("/user", {
				email: form.email.content,
				password: form.password.content,
				firstname: form.firstname.content,
				lastname: form.lastname.content,
			})
				.then((response) => {
					user.email = response.data.email;
					return {
						ok: true,
					};
				})
				.catch((error) => {
					return {
						ok: false,
						message: error.response.data.message,
					};
				});
		}

		function signOut() {
			user.id = "";
			user.email = "";
			user["access-token"] = "";
			user["refresh-token"] = "";
		}

		return { user, signIn, signOut };
	},
	{
		persist: true,
	}
);
