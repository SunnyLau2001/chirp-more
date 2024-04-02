<script setup lang="ts">
import { followingState } from "@/Utils/store";
import { computed, ref } from "vue";
import Modal from "./Modal.vue";

const props = defineProps<{
	showFollowingList: boolean;
}>();

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

// Used to track which following is handling
const unfollowingId = ref<null | number>(null);
const unfollowUser = async (user_id: number, following_id: number) => {
	unfollowingId.value = following_id;
	const id = parseInt(`${user_id}${following_id}`);

	try {
		const response = await window.axios.delete(route("userfollowing.destroy", id));

		console.log(response);

		if (!response || response.data.status !== "success") return;

		followingState.removeFollowFromMapById(following_id);
	} catch (e) {
		console.log(e);
	} finally {
		unfollowingId.value = null;
	}
};
</script>

<template>
	<Modal :show="props.showFollowingList">
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
					<a :href="`/user/${following.following?.id}`">
						<span class="hover:underline">{{ following.following?.name || "User Missing" }}</span>
					</a>
					<span v-if="unfollowingId === following.following_id">Loading</span>
					<button v-else class="underline underline-offset-2" @click="unfollowUser(following.user_id, following.following_id)">Unfollow</button>
				</div>
			</div>
		</div>
	</Modal>
</template>
