<script setup lang="ts">
import Chirp from "@/Components/Chirp.vue";
import type { ChirpProps } from "@/types/chirp";
import { Head, Link, usePage } from "@inertiajs/vue3";
import axios from "axios";
import { ref } from "vue";
import { computed } from "vue";

interface Props {
	canLogin?: boolean;
	canRegister?: boolean;
	laravelVersion: string;
	phpVersion: string;
	chirps: ChirpProps[];
	message?: string;
	userCounts?: number;
	chirpCounts?: number;
}

// defineProps<{
// 	canLogin?: boolean;
// 	canRegister?: boolean;
// 	laravelVersion: string;
// 	phpVersion: string;
// }>();
const props = withDefaults(defineProps<Props>(), {
	message: "Hello",
	userCounts: 0,
	chirpCounts: 0,
});

const page = usePage();
const user = computed(() => {
	return page.props.auth.user;
});

const chirps = ref(props.chirps);
const chirpCounts = ref(props.chirpCounts);

window.Echo.channel(`emit-chirp`).listen("ChirpCreated", (e: any) => {
	const newChirp: ChirpProps = e.chirp || null;
	if (newChirp) chirps.value.unshift(newChirp);
});
</script>

<template>
	<Head title="Welcome" />

	<div class="relative flex justify-center items-center min-h-screen selection:bg-red-500 selection:text-white">
		<div v-if="props.canLogin" class="fixed top-0 right-0 p-6 text-end">
			<Link v-if="user" :href="route('chirps.index')" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Chirps</Link>

			<template v-else>
				<Link :href="route('login')" class="focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</Link>

				<Link v-if="props.canRegister" :href="route('register')" class="ms-4 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</Link>
			</template>
		</div>

		<div class="max-w-2xl mx-auto p-4 sm:p-0 max-h-dvh absolute my-auto">
			<div class="py-8">
				<h1 class="text-center text-4xl">Join The <span class="">Chirp</span>!</h1>
			</div>
			<div class="stat flex gap-2">
				<div class="w-1/2 border rounded-lg px-5 py-3" aria-label="chirp counts">
					<p class="text-gray-400">Chirps</p>
					<p class="text-2xl">{{ chirpCounts }}</p>
				</div>
				<div class="w-1/2 border rounded-lg px-5 py-3" aria-label="user counts">
					<p class="text-gray-400">Users</p>
					<p class="text-2xl">{{ props.userCounts }}</p>
				</div>
			</div>
			<div class="border rounded-lg overflow-clip mt-4">
				<div class="px-5 py-3 border-b text-gray-400">
					<p>Explore the timeline</p>
				</div>
				<div class="demo-box h-[50dvh] overflow-y-scroll overflow-clip">
					<div class="bg-white shadow-sm rounded-lg divide-y">
						<Chirp v-for="chirp in chirps" :key="chirp.id" :chirp="chirp" :currentUser="user" />
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
