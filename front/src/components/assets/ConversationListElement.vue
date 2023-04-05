<script setup>
import { computed } from "vue";

const props = defineProps({
	message: {
		type: Object,
		required: true,
	},
});

const date = computed(() => {
	const date = new Date(props.message.created_at);
	return date.toLocaleDateString("fr-FR", {
		year: "numeric",
		month: "numeric",
		day: "numeric",
	});
});
</script>

<template>
	<div class="conversation-list-element">
		<div class="conversation-list-element__user">
			<div class="conversation-list-element__user__name">{{ message.user.firstname }} {{ message.user.lastname }}</div>
			<div class="conversation-list-element__user__date">{{ date }}</div>
		</div>
		<div class="conversation-list-element__message">
			{{ message.comment }}
		</div>
	</div>
</template>

<style lang="scss">
.conversation-list-element {
	display: flex;
	flex-direction: column;
	width: 100%;
	padding: 1rem;
	border-bottom: 1px solid #e0e0e0;
	transition: all 0.2s ease-in-out;

	&:hover {
		background-color: #f5f5f5;
	}

	&__user {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		width: 100%;
		height: 100%;
		margin-bottom: 1rem;

		&__name {
			font-size: 1.2rem;
			font-weight: 600;
		}
	}

	&__message {
		display: flex;
		flex-direction: row;
		width: 100%;
		height: 100%;
		font-size: 1rem;
		font-weight: 400;
	}
}
</style>
