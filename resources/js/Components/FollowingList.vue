<script setup lang="ts">
import { followingState } from "@/Utils/store";
import { computed, ref } from "vue";
import Modal from "./Modal.vue";

const followings = computed(() => {
	return Array.from(followingState.followings.values());
});

const updating = computed(() => {
	return followingState.updating;
});

const emit = defineEmits(["showFollowingList"]);
const openModel = ref(false);

const close = () => {
	openModel.value = false;
	emit("showFollowingList", false);
};
</script>

<template>
	<Modal :show="true">
		<div class="relative p-4">
			<button class="fixed right-4" @click="close">Close</button>
			<div class="w-full mt-6">
				<p class="text-gray-400">Followings</p>
				<div v-if="updating">
					<svg class="animate-spin h- w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
						<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
						<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
					</svg>
				</div>
				<div v-else v-for="following in followings" class="w-full flex justify-between">
					<a href="/">
						<span class="hover:underline">{{ following.following.name }}</span>
					</a>
				</div>
			</div>
		</div>
	</Modal>
</template>
