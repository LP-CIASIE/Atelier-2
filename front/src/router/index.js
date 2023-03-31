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
			path: "/settings",
			name: "settings",
			component: () => import("@/views/ProfileView.vue"),
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
