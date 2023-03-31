<template>
	<nav class="navbar">
		<router-link class="navbar__brand" to="/">Tedyspo</router-link>
		<div class="navbar__links">
			<router-link to="/profile">Profile</router-link>
			<router-link to="/settings">Paramètres</router-link>
			<router-link to="/sign-out">Déconnexion</router-link>
		</div>
	</nav>
</template>

<style lang="scss" scoped>
@import "@/assets/scss/reset.scss";

.navbar {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 2rem 1.5rem;
	height: 3rem;
	background-color: $color-bg-navbar;
	color: #fff;
	a {
		color: #fff;
	}

	&__brand {
		font-size: 1.5rem;
		font-weight: 500;
	}

	&__links {
		display: flex;
		justify-content: space-between;
		align-items: center;
		gap: 1rem;
	}
}
</style>
