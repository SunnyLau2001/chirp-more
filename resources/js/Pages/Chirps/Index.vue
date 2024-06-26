<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm, Head, usePage } from "@inertiajs/vue3";
import Chirp from "@/Components/Chirp.vue";
import { computed } from "vue";
import { ChirpProps } from "@/types/chirp";
import { ref } from "vue";
import Echo from "laravel-echo";
import axios from "axios";
import { followingState } from "@/Utils/store";

// Injected from the ChirpController when hitting the index route.
const props = defineProps<{
	chirps: ChirpProps[];
}>();

const form = useForm({
	message: "",
});

// Testing for access user data through inertiajs
// You can access user data from here, or pass during accessing the controller
const page = usePage();
const user = computed(() => {
	return {
		id: page.props.auth.user.id,
		name: page.props.auth.user.name,
	};
});

const chirps = ref(props.chirps);

window.Echo.channel(`emit-chirp`).listen("ChirpCreated", (e: any) => {
	const newChirp: ChirpProps = e.chirp || null;
	if (newChirp) chirps.value.unshift(newChirp);
});
</script>

<template>
	<Head title="Chirps" />

	<AuthenticatedLayout>
		<div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
			<form @submit.prevent="form.post(route('chirps.store'), { onSuccess: () => form.reset() })">
				<textarea v-model="form.message" placeholder="What's on your mind?" class="block w-full border-gray-300 focus:border-amber-300 focus:ring focus:ring-amber-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
				<InputError :message="form.errors.message" class="mt-2" />
				<PrimaryButton class="mt-4">Chirp</PrimaryButton>
			</form>
			<div class="mt-6 bg-white shadow-sm border border-gray-100 rounded-lg divide-y">
				<Chirp v-for="chirp in chirps" :key="chirp.id" :chirp="chirp" :currentUser="user" />
			</div>
		</div>
	</AuthenticatedLayout>
</template>
