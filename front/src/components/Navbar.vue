<script setup>
import Menubar from "primevue/menubar";
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useSessionStore } from "@/stores/session.js";

const router = useRouter();
const Session = useSessionStore();

const items = ref([
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
]);
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
