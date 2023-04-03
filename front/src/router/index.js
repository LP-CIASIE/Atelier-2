import { createRouter, createWebHistory } from "vue-router";

import HomeView from "@/views/HomeView.vue";

const router = createRouter({
	history: createWebHistory(import.meta.env.BASE_URL),
	routes: [
		{
			path: "",
			name: "home",
			component: HomeView,
		},
		{
			path: "/test",
			name: "test",
			component: () => import("@/views/TestPrimeView.vue"),
		},
		{
			path: "/sign-in",
			name: "signIn",
			component: () => import("@/views/SignInView.vue"),
		},
		{
			path: "/sign-up",
			name: "signUp",
			component: () => import("@/views/SignUpView.vue"),
		},
		{
			path: "/profile",
			name: "profile",
			component: () => import("@/views/ProfileView.vue"),
		},
		{
			path: "/event",
			redirect: "/",
			children: [
				{
					path: ":id",
					name: "event",
					component: () => import("@/views/event/EventView.vue"),
				},
				{
					path: "create",
					name: "createEvent",
					component: () => import("@/views/event/CreateEventView.vue"),
				},
				{
					path: "edit/:id",
					name: "editEvent",
					component: () => import("@/views/event/EditEventView.vue"),
				},
			],
		},
	],
});

router.afterEach((to, from) => {
	// scoll top smoothly
	window.scrollTo({
		top: 0,
		behavior: "smooth",
	});
});

export default router;
