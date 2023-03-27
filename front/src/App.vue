<script setup>
import { RouterView } from "vue-router";
</script>

<template>
	<header>
		<Navbar />
	</header>

	<article>
		<router-view v-slot="{ Component }">
			<transition name="fade" mode="out-in">
				<component :is="Component" />
			</transition>
		</router-view>
	</article>

	<Footer />
</template>

<style lang="scss">
@import "@/assets/scss/reset.scss";
@import "@/assets/scss/basic.scss";
</style>
