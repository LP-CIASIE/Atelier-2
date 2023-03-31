<script setup>
import Menubar from "primevue/menubar";
import { ref } from "vue";

const items = ref([
	{
		label: "Profile",
		icon: "pi pi-fw pi-user",
		items: [
			{
				label: "Account",
				icon: "pi pi-fw pi-user",
				to: "/profile",
			},
			{
				label: "Settings",
				icon: "pi pi-fw pi-cog",
				to: "/settings",
			},
		],
	},
	{
		label: "Disconnect",
		icon: "pi pi-fw pi-power-off",
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
