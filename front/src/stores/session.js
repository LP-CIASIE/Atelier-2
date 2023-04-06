import { defineStore } from "pinia";
import { inject, reactive } from "vue";
import { useRouter } from "vue-router";

export const useSessionStore = defineStore("session", {
	state: () => {
		const router = useRouter();
		const API = inject("api");
		const BUS = inject("bus");
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
				.then((response) => {
					user.id = response.data.id_user;
					user.access_token = response.data["access-token"];
					user.refresh_token = response.data["refresh-token"];
					return getUser().then((userResponse) => {
						user.email = userResponse.user.email;
						user.firstname = userResponse.user.firstname;
						user.lastname = userResponse.user.lastname || "";
						return {
							ok: true,
						};
					});
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
					return {
						ok: true,
						user: response.data.user,
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
			return API.put(
				"/users",
				{
					email: form.email.content,
					password: form.password.content,
					firstname: form.firstname.content,
					lastname: form.lastname.content,
				},
				{
					headers: {
						Authorization: `Bearer ${user.access_token}`,
					},
				}
			)
				.then((response) => {
					user.email = form.email.content;
					user.firstname = form.firstname.content;
					user.lastname = form.lastname.content;

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

		async function deleteUser() {
			return API.delete(`/users/${user.id}`, {
				headers: {
					Authorization: `Bearer ${user.access_token}`,
				},
			})
				.then((response) => {
					signOut();
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
			user.firstname = "";
			user.lastname = "";
			user.access_token = "";
			user.refresh_token = "";

			BUS.emit("disconnect");
			router.push({ name: "signIn" });
		}

		return { user, signIn, signOut, updateUser, getUser, deleteUser };
	},
	persist: true,
});
