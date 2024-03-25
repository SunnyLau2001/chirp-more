<script setup lang="ts">
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
	<div class="min-h-screen flex flex-col justify-center items-center sm:pt-0">
		<div>
			<Link href="/">
				<ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
			</Link>
		</div>

		<div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white sm:shadow-md overflow-hidden sm:border sm:rounded-lg">
			<slot />
		</div>
	</div>
</template>
