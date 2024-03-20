<script setup lang="ts">
import Chirp from "@/Components/Chirp.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

defineProps(["chirps", "likes"]);
const page = usePage();
const user = computed(() => {
	return {
		id: page.props.auth.user.id,
		name: page.props.auth.user.name,
	};
});

const userChirps = computed(() => {
	return page.props.chirps as any;
});
</script>

<template>
	<Head title="Chirps" />

	<AuthenticatedLayout>
		<div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
			<div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
				<Chirp v-for="chirp in userChirps" :key="chirp.id" :chirp="chirp" :user="user" />
			</div>
		</div>
	</AuthenticatedLayout>
</template>
