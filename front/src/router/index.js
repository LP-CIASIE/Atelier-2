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
