<script setup>
import Menubar from "primevue/menubar";
import { ref, inject } from "vue";
import { useRouter } from "vue-router";
import { useSessionStore } from "@/stores/session.js";

const router = useRouter();
const Session = useSessionStore();
const BUS = inject("bus");

const items = ref([]);

const listLog = [
	{
		label: "Evénements",
		icon: "pi pi-fw pi-calendar",
		to: { name: "home" },
	},
	{
		label: "Profil",
		icon: "pi pi-fw pi-user",
		to: { name: "profile" },
	},
	{
		label: "Déconnexion",
		icon: "pi pi-fw pi-power-off",
		command: () => {
			Session.signOut();
		},
	},
];

const listNotLog = [
	{
		label: "Inscription",
		icon: "pi pi-fw pi-user-plus",
		to: { name: "signUp" },
	},
	{
		label: "Connexion",
		icon: "pi pi-fw pi-sign-in",
		to: { name: "signIn" },
	},
];

if (Session.user.id) {
	items.value = listLog;
} else {
	items.value = listNotLog;
}

BUS.on("connection", () => {
	items.value = listLog;
});

BUS.on("disconnect", () => {
	items.value = listNotLog;
});
</script>

<template>
	<div class="card relative z-2">
		<Menubar :model="items">
			<template #start><router-link to="/" id="brand">Tedyspo</router-link></template>
		</Menubar>
	</div>
</template>

<style lang="scss" scoped>
.p-menubar {
	justify-content: space-between;

	#brand {
		font-size: 1.5rem;
		color: rgba(0, 0, 0, 0.9);
		padding: 0.5rem 1rem;
	}
}
</style>
