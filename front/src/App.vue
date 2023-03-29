<script setup>
import { RouterView } from "vue-router";
import { provide } from "vue";
import axios from "axios";


// Variable Globale pour Axios nommé "api"
const API = axios.create({
	baseURL: "http://gateway.atelier.local:8000",
	headers: {
		"Content-Type": "application/json",
		// Authorization: "b85abe7b-7412-456f-9b9c-377e21ffcb33",
	},
	mode: 'cors'
});
provide("api", API);

</script>

<template>
	<!-- <header>
														<Navbar />
													</header> -->

	<article>
		<router-view v-slot="{ Component }">
			<transition name="fade" mode="out-in">
				<component :is="Component" />
			</transition>
		</router-view>
	</article>

	<!-- <Footer /> -->
</template>

<style lang="scss">
@import "@/assets/scss/reset.scss";
@import "@/assets/scss/basic.scss";
</style>
