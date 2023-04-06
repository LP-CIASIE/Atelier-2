import { createRouter, createWebHistory } from "vue-router";

import HomeView from "@/views/HomeView.vue";

const router = createRouter({
	history: createWebHistory(import.meta.env.BASE_URL),
	routes: [
		{
			path: "",
			name: "home",
			component: HomeView,
			meta: {
				title: "Accueil - Tedyspo",
				auth: true,
			},
		},
		{
			path: "/sign-in",
			name: "signIn",
			component: () => import("@/views/SignInView.vue"),
			meta: {
				title: "Connexion - Tedyspo",
				auth: false,
			},
		},
		{
			path: "/sign-up",
			name: "signUp",
			component: () => import("@/views/SignUpView.vue"),
			meta: {
				title: "Inscription - Tedyspo",
				auth: false,
			},
		},
		{
			path: "/profile",
			name: "profile",
			component: () => import("@/views/ProfileView.vue"),
			meta: {
				title: "Profil - Tedyspo",
				auth: true,
			},
		},
		{
			path: "/event",
			redirect: "/",
			children: [
				{
					path: ":id",
					name: "event",
					component: () => import("@/views/event/EventView.vue"),
					meta: {
						title: "Événement - Tedyspo",
						auth: false,
					},
				},
				{
					path: "create",
					name: "createEvent",
					component: () => import("@/views/event/CreateEventView.vue"),
					meta: {
						title: "Créer un événement - Tedyspo",
						auth: true,
					},
				},
				{
					path: "waiting-list",
					name: "waitingListEvent",
					component: () => import("@/views/event/WaitingListView.vue"),
					meta: {
						title: "Événements en attente - Tedyspo",
						auth: true,
					},
				},
			],
		},
	],
});

router.beforeEach((to, from) => {
	// scroll top smoothly
	window.scrollTo({
		top: 0,
		behavior: "smooth",
	});

	// set title
	document.title = to.meta.title;

	// set auth
	if (to.meta.auth) {
		if (!localStorage.getItem("session")) {
			router.push("/sign-in");
		} else {
			let session = JSON.parse(localStorage.getItem("session"));

			if (!session.user || !session.user.id || !session.user.access_token || !session.user.refresh_token || !session.user.email) {
				router.push("/sign-in");
			}
		}
	}
});

export default router;
