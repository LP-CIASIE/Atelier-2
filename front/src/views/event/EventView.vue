<script setup>
import { ref, reactive, inject, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useSessionStore } from "@/stores/session.js";

const Session = useSessionStore();
const router = useRouter();
const route = useRoute();
const API = inject("api");

function getRequest(url) {
	return API.get(url, {
		headers: {
			Authorization: "Bearer " + Session.user.access_token,
		},
	})
		.then((response) => {
			let data = response.data;
			console.log(data);
			return data;
		})
		.catch((error) => {
			console.log(error);
		});
}

const event = reactive({
	title: "",
	description: "",
	date: "",
	is_public: "",
	owner: {},
	participants: [],
	locations: [],
	links: [],
	comments: [],
});

function getEvent() {
	getRequest(`/events/${route.params.id}`).then((data) => {
		event.title = data.title;
		event.description = data.description;
		event.date = data.date;
		event.is_public = data.is_public;

		let links = data.event.links;
		getOwner(links.owner.href);

		// Attendre les routes API
		// getParticipants(links.participants.href);
		// getLocations(links.locations.href);
		// getLinks(links.urls.href);
		// getComments(links.comments.href);
	});
}

function getParticipants(url) {
	getRequest(url).then((data) => {
		event.participants = data.participants;
	});
}

function getLocations(url) {
	getRequest(url).then((data) => {
		event.locations = data.locations;
	});
}

function getLinks(url) {
	getRequest(url).then((data) => {
		event.links = data.urls;
	});
}

function getComments(url) {
	getRequest(url).then((data) => {
		event.comments = data.comments;
	});
}

function getOwner(url) {
	getRequest(url).then((data) => {
		event.owner = data.user;
	});
}

onMounted(() => {
	getEvent();
});
</script>

<template></template>

<style lang="scss" scoped></style>
