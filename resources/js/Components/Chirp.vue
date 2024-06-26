<script setup lang="ts">
import { useForm, usePage } from "@inertiajs/vue3";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import { computed, ref } from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import axios from "axios";
import type { ChirpProps, LikeProps } from "@/types/chirp";
import { followingState } from "@/Utils/store";

dayjs.extend(relativeTime);

// Display a single chirp with a chirp data
// Injected chirp from the page calling this component
// !Not the chirps from the controller
const props = defineProps<{
	chirp: ChirpProps;
	currentUser: {
		id: number;
		name: string;
	} | null;
}>();

const form = useForm({
	message: props.chirp.message,
});

const editing = ref(false);

const liked = ref(false);
const sortLikeList = () => {
	if (!props.chirp.likes) return [];

	// Move user's like to the first item if found
	const likedIndex = props.chirp.likes.findIndex((like: LikeProps) => like.user_id === props.currentUser?.id);
	if (likedIndex !== -1) {
		// Move user like to the first element
		[props.chirp.likes[likedIndex], props.chirp.likes[0]] = [props.chirp.likes[0], props.chirp.likes[likedIndex]];
		console.log(props.chirp.likes);
		liked.value = true;
	}

	return props.chirp.likes;
};
const likeList = ref(sortLikeList());
const getMyLike = (likes: LikeProps[]) => {
	// exit if likeList no element
	if (likeList.value.length === 0) return null;

	if (likeList.value[0].user_id === props.currentUser?.id) return likeList.value[0];

	return null;
};
const myLike = ref(getMyLike(likeList.value));

const updatingLike = ref(false);
const createLike = async () => {
	try {
		const response = await axios.post(route("likes.store"), {
			user_id: props.currentUser?.id,
			chirp_id: props.chirp.id,
		});

		likeList.value.unshift(response.data);
		myLike.value = response.data;
		liked.value = true;
	} catch {
	} finally {
		updatingLike.value = false;
	}
};
const deleteLike = async () => {
	if (myLike.value === null) return;

	try {
		console.log(route("likes.destroy", myLike.value?.id));
		const response = await axios.delete(route("likes.destroy", myLike.value?.id));

		if (response.data.result == true) {
			likeList.value = likeList.value.filter((like: any) => like.user_id !== props.currentUser?.id);
			myLike.value = null;
			liked.value = false;
		}
	} catch {}
};

const handleLike = async (e: MouseEvent) => {
	// remember to use .value in script
	updatingLike.value = true;

	if (myLike.value === null) {
		console.log(null);
		await createLike();
	} else {
		await deleteLike();
	}

	updatingLike.value = false;
};

const isHandlingFollow = ref(false);
const suspenFollowBtn = computed(() => {
	return isHandlingFollow.value || followingState.updating;
});
const followUser = async (e: MouseEvent) => {
	if (props.currentUser === null) return;

	isHandlingFollow.value = true;

	try {
		const response = await axios.post(route("userfollowing.store"), {
			user_id: props.currentUser.id,
			following_id: props.chirp.user_id,
		});

		console.log(response);

		if (!response || response.data.status !== "success" || response.data.user_following === null) return;

		followingState.updateMapWithNewFollow(response.data.user_following);
	} catch {
	} finally {
		isHandlingFollow.value = false;
	}
};
const unfollowUser = async () => {
	isHandlingFollow.value = true;

	if (props.currentUser === null) return;

	const id = parseInt(`${props.currentUser.id}${props.chirp.user_id}`);

	try {
		const response = await axios.delete(route("userfollowing.destroy", id));

		console.log(response);

		if (!response || response.data.status !== "success") return;

		followingState.removeFollowFromMapById(props.chirp.user_id);
	} catch {
	} finally {
		isHandlingFollow.value = false;
	}
};

const isFollowed = computed(() => {
	// Depends on the state of followingMap
	return followingState.followings.get(props.chirp.user_id) || null;
});
</script>

<template>
	<div>
		<div class="p-6 flex space-x-2">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
				<path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
			</svg>
			<div class="flex-1">
				<div class="flex justify-between items-center">
					<div>
						<span class="text-gray-800">{{ chirp.user.name }}</span>
						<small class="ml-2 text-sm text-gray-600">{{ dayjs(chirp.created_at).fromNow() }}</small>
						<small v-if="chirp.created_at !== chirp.updated_at" class="text-sm text-gray-600"> &middot; edited</small>
					</div>
					<!-- Follow button -->
					<div v-if="props.currentUser !== null && chirp.user.id !== $page.props.auth.user?.id" class="follow-btn">
						<div v-if="suspenFollowBtn">
							<span>Loading</span>
						</div>
						<div v-else>
							<button v-if="isFollowed" class="underline underline-offset-2" @click="unfollowUser">Unfollow</button>
							<button v-else @click="followUser">Follow</button>
						</div>
					</div>
					<Dropdown v-if="chirp.user.id === $page.props.auth.user?.id">
						<template #trigger>
							<button>
								<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
									<path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
								</svg>
							</button>
						</template>
						<template #content>
							<button class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out" @click="editing = true">Edit</button>
							<DropdownLink as="button" :href="route('chirps.destroy', chirp.id)" method="delete"> Delete </DropdownLink>
						</template>
					</Dropdown>
				</div>
				<form v-if="editing" @submit.prevent="form.put(route('chirps.update', chirp.id), { onSuccess: () => (editing = false) })">
					<textarea v-model="form.message" class="mt-4 w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
					<InputError :message="form.errors.message" class="mt-2" />
					<div class="space-x-2">
						<PrimaryButton class="mt-4">Save</PrimaryButton>
						<button
							class="mt-4"
							@click="
								editing = false;
								form.reset();
								form.clearErrors();
							"
						>
							Cancel
						</button>
					</div>
				</form>
				<p v-else class="mt-4 text-lg text-gray-900">
					{{ chirp.message }}
				</p>
			</div>
		</div>
		<!-- stats -->
		<div class="px-6 flex gap-2 items-center pb-5" v-if="likeList.length > 0">
			<span>Liked by:</span>
			<ul class="flex">
				<li v-for="(like, index) in likeList.slice(0, 3)">
					<span v-if="like.user_id == props.currentUser?.id">Me</span>
					<span v-else>
						{{ index == 0 ? `${like.user.name}` : `, ${like.user.name}` }}
					</span>
					<span v-if="index == 2 && likeList.length > 3">{{ `, and other ${likeList.length - 3} people...` }}</span>
				</li>
			</ul>
		</div>
		<!-- utils -->
		<div v-if="currentUser !== null" class="utils">
			<div class="px-3">
				<div v-if="updatingLike" class="w-12 h-12 flex justify-center items-center">
					<svg class="animate-spin h- w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
						<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
						<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
					</svg>
				</div>
				<button v-else @click="handleLike">
					<div class="w-12 h-12 flex justify-center items-center">
						<svg v-if="liked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#f87171" class="w-6 h-6">
							<path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
						</svg>
						<svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
							<path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
						</svg>
					</div>
				</button>
			</div>
		</div>
	</div>
</template>
