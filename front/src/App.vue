<script setup>
import Navbar from "@/components/Navbar.vue";

import { RouterView } from "vue-router";
import { provide } from "vue";
import axios from "axios";
import mitt from "mitt";

// Variable Globale pour Axios nommé "api"
const API = axios.create({
	baseURL: "http://gateway.atelier.local:8000",
	headers: {
		"Content-Type": "application/json",
		// Authorization: "b85abe7b-7412-456f-9b9c-377e21ffcb33",
	},
	mode: "cors",
});

provide("api", API);
provide("bus", mitt());
</script>

<template>
	<template v-if="!['/sign-in', '/sign-up'].includes($route.path)">
		<header>
			<Navbar />
		</header>
	</template>

	<article class="container">
		<router-view v-slot="{ Component }">
			<component :is="Component" />
		</router-view>
	</article>

	<!-- <Footer /> -->
</template>

<style lang="scss">
@import "@/assets/scss/reset.scss";
@import "@/assets/scss/basic.scss";
</style>
