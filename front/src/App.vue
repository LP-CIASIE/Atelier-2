<script setup>
import Navbar from "@/components/Navbar.vue";

import { RouterView } from "vue-router";
import { provide } from "vue";
import { useSessionStore } from "@/stores/session.js";
import axios from "axios";
import mitt from "mitt";

// Variable Globale pour Axios nommé "api"
const API = axios.create({
	baseURL: "https://api.tedyspo.cyprien-cotinaut.com",
	headers: {
		"Content-Type": "application/json",
	},
	mode: "cors",
});

API.getActionRequest = (url, params) => {
	const Session = useSessionStore();

	const paramsRequest = params ? params : {};

	return API.get(url, {
		params: paramsRequest,
		headers: {
			Authorization: "Bearer " + Session.user.access_token,
		},
	})
		.then((response) => {
			return response.data;
		})
		.catch((error) => {
			return error;
		});
};

API.postActionRequest = (url, params, data) => {
	const Session = useSessionStore();

	const paramsRequest = {
		...params,
	};

	return API.post(url, data, {
		params: paramsRequest,
		headers: {
			Authorization: "Bearer " + Session.user.access_token,
		},
	})
		.then((response) => {
			return response.data;
		})
		.catch((error) => {
			return error;
		});
};

API.putActionRequest = (url, params, data) => {
	const Session = useSessionStore();

	const paramsRequest = {
		...params,
	};

	return API.put(url, data, {
		params: paramsRequest,
		headers: {
			Authorization: "Bearer " + Session.user.access_token,
		},
	})
		.then((response) => {
			return response.data;
		})
		.catch((error) => {
			return error;
		});
};

API.deleteActionRequest = (url, params) => {
	const Session = useSessionStore();

	const paramsRequest = {
		...params,
	};

	return API.delete(url, {
		params: paramsRequest,
		headers: {
			Authorization: "Bearer " + Session.user.access_token,
		},
	})
		.then((response) => {
			return response.data;
		})
		.catch((error) => {
			return error;
		});
};

provide("api", API);
provide("bus", mitt());
</script>

<template>
	<header>
		<Navbar />
	</header>

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
