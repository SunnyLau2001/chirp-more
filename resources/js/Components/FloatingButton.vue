<script setup lang="ts">
import { onUnmounted } from "vue";
import { onMounted } from "vue";
import { ref } from "vue";

const updateFloatButtonState = (e: Event) => {
	const lastKnownScrollPosition = window.scrollY;

	if (lastKnownScrollPosition > 100) {
		window.requestAnimationFrame(() => {
			showFloatButton.value = true;
		});
	} else {
		window.requestAnimationFrame(() => {
			showFloatButton.value = false;
		});
	}
};

const expandMenuOnClick = (e: MouseEvent) => {
	expandMenu.value = !expandMenu.value;
};

onMounted(() => window.addEventListener("scroll", updateFloatButtonState));
onUnmounted(() => window.addEventListener("scroll", updateFloatButtonState));

const showFloatButton = ref(false);
const expandMenu = ref(false);
</script>

<template>
	<div class="float-button-container fixed bottom-8 right-8" :class="{ active: showFloatButton, hidden: !showFloatButton }">
		<div id="float-button-menu" class="mb-3 flex flex-col gap-3" :class="{ active: expandMenu, hidden: !expandMenu }">
			<div class="float-button-menu_items w-12 h-12 border rounded-full">item1</div>
			<div class="float-button-menu_items w-12 h-12 border rounded-full">item2</div>
		</div>

		<!-- Expand menu when clicked -->
		<button class="float-button w-12 h-12 border rounded-full grid place-items-center" :aria-expanded="expandMenu" aria-controls="float-button-menu" @click="expandMenuOnClick">
			<span class="text-sm"
				><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-gray-300">
					<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
				</svg>
			</span>
		</button>
	</div>
</template>
