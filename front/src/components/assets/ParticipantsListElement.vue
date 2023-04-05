<script setup>
import Card from "primevue/card";
import Tag from "primevue/tag";
import Skeleton from "primevue/skeleton";
import Divider from "primevue/divider";

import { ref, reactive, inject, onMounted } from "vue";
import { useSessionStore } from "@/stores/session.js";

const props = defineProps({
	participant: {
		type: Object,
		required: true,
	},
});

const Session = useSessionStore();

onMounted(() => {
	console.log(props.participant);
});
</script>

<template>
	<Card class="mt-3 participant" :class="{ organisator: participant.status_event.is_organisator, you: participant.id == Session.user.id }">
		<template #content>
			<div class="content">
				<template v-if="participant.id !== ''">
					<div class="info">
						<p>{{ participant.firstname }} {{ participant.lastname }} <i v-if="participant.status_event.is_organisator" class="pi pi-shield color" title="Organisateur de l'événement"></i></p>
						<p class="mail" v-if="participant.status_event.is_organisator">{{ participant.email }}</p>
					</div>
					<div class="tag">
						<Tag v-if="participant.status_event.is_here" value="Sur place" class="p-tag-rounded" />
						<Tag v-if="participant.status_event.state == 'accepted' && !participant.status_event.is_here" value="Accepté" class="p-tag-rounded p-tag-success" />
						<Tag v-if="participant.status_event.state == 'pending'" value="En attente" class="p-tag-rounded p-tag-warning" />
						<Tag v-if="participant.status_event.state == 'refused'" value="Refusé" class="p-tag-rounded p-tag-danger" />
					</div>
				</template>
				<template v-else>
					<div class="info">
						<Skeleton width="5rem" height="1.2rem" />
						<Skeleton width="10rem" height="1.2rem" v-if="participant.status_event.is_organisator" />
					</div>
					<div class="tag">
						<Skeleton width="3.5rem" height="1.5rem" />
					</div>
				</template>
				<Card class="comment" v-if="!participant.status_event.is_organisator && participant.status_event.state != 'pending'">
					<template #content>
						<div>
							<p>{{ participant.status_event.comment }}</p>
						</div>
					</template>
				</Card>
			</div>
		</template>
	</Card>

	<Divider v-if="participant.status_event.is_organisator" />
</template>

<style lang="scss">
.participant {
	width: 350px;
	max-width: 350px;

	&.you {
		border: 2px solid hsl(240, 40%, 70%);
	}

	&.organisator {
		background-color: hsl(240, 40%, 93%);

		.info {
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			height: 50px;
		}
	}

	.p-card-content {
		padding: 0;
		.content {
			position: relative;
		}
		.tag {
			position: absolute;
			right: 0;
			top: -3px;

			display: flex;
			gap: 0.3rem;
		}

		p {
			margin: 0;

			.color {
				color: #3f51b5;
				margin-left: 0.5rem;
			}

			.p-tag {
				margin-left: 0.5rem;
			}

			&.mail {
				font-size: 0.8rem;
				color: hsla(0, 0%, 0%, 0.6);
			}
		}
	}
	.p-card.comment {
		max-height: 0;
		overflow: hidden;
		margin: 0;
		padding: 0;

		transition: max-height 0.2s ease-in-out, margin 0.4s ease-in-out;
	}

	&:hover .p-card.comment {
		max-height: 150px;
		margin-top: 1rem;
	}
}
</style>
