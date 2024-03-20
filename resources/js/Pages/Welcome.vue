<script setup lang="ts">
import Chirp from "@/Components/Chirp.vue";
import type { ChirpProps } from "@/types/chirp";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

interface Props {
	canLogin?: boolean;
	canRegister?: boolean;
	laravelVersion: string;
	phpVersion: string;
	chirps: ChirpProps[];
	message?: string;
}

// defineProps<{
// 	canLogin?: boolean;
// 	canRegister?: boolean;
// 	laravelVersion: string;
// 	phpVersion: string;
// }>();
const props = withDefaults(defineProps<Props>(), {
	message: "Hello",
});

const page = usePage();
const user = computed(() => {
	return page.props.auth.user;
});
</script>

<template>
	<Head title="Welcome" />

	<div class="relative flex justify-center items-center min-h-screen selection:bg-red-500 selection:text-white">
		<div v-if="props.canLogin" class="fixed top-0 right-0 p-6 text-end">
			<Link v-if="user" :href="route('dashboard')" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</Link>

			<template v-else>
				<Link :href="route('login')" class="focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</Link>

				<Link v-if="props.canRegister" :href="route('register')" class="ms-4 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</Link>
			</template>
		</div>

		<div class="max-w-2xl mx-auto p-4 sm:p-0 max-h-dvh absolute my-auto">
			<div class="py-8">
				<h1 class="text-center text-4xl">Join The <span class="">Chirp</span>!</h1>
			</div>
			<div class="demo-box h-[50dvh] overflow-y-scroll border rounded-lg overflow-clip">
				<div class="bg-white shadow-sm rounded-lg divide-y">
					<Chirp v-for="chirp in props.chirps" :key="chirp.id" :chirp="chirp" :currentUser="user" />
				</div>
			</div>
		</div>
	</div>
</template>
