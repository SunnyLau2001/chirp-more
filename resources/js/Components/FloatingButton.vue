<script setup lang="ts">
import { onUnmounted, computed, onMounted } from "vue";
import { ref } from "vue";
import FollowingList from "./FollowingList.vue";

const updateFloatButtonState = (e: Event) => {
	const lastKnownScrollPosition = window.scrollY;

	if (lastKnownScrollPosition > 100) {
		window.requestAnimationFrame(() => {
			showFloatButton.value = true;
		});
	} else {
		window.requestAnimationFrame(() => {
			showFloatButton.value = false;
			expandMenu.value = false;
		});
	}
};

const expandMenuOnClick = (e: MouseEvent) => {
	expandMenu.value = !expandMenu.value;
};

const showFollowingList = ref(false);
const handleShowFollowingList = (show: boolean) => {
	showFollowingList.value = show;
	console.log(show);
};

onMounted(() => window.addEventListener("scroll", updateFloatButtonState));
onUnmounted(() => window.addEventListener("scroll", updateFloatButtonState));

const showFloatButton = ref(false);
const expandMenu = ref(false);
</script>

<template>
	<div class="float-button-container fixed bottom-8 right-8 transition z-10" :class="{ 'translate-y-20': !showFloatButton }">
		<!-- Expand menu when clicked -->
		<button class="float-button w-12 h-12 border rounded-full grid place-items-center bg-white hover:bg-zinc-100 active:bg-zinc-200 transition" :aria-expanded="expandMenu" aria-controls="float-button-menu" @click="expandMenuOnClick">
			<span class="text-sm"
				><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-gray-300">
					<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
				</svg>
			</span>
		</button>

		<div id="float-button-menu" class="transition absolute bottom-0 -z-10" :class="{ 'opacity-100': expandMenu, 'opacity-0': !expandMenu }">
			<button class="absolute z-0 w-12 h-12 border rounded-full grid place-items-center bottom-[8px] hover:bg-zinc-100 active:bg-zinc-200 transition" :class="{ '-translate-y-[48px]': expandMenu }" @click="handleShowFollowingList(true)">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-300">
					<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
				</svg>
			</button>
			<button class="absolute z-[-1] w-12 h-12 bottom-[calc(8px*2)] border rounded-full transition hover:bg-zinc-100 active:bg-zinc-200" :class="{ '-translate-y-[calc(48px*2)]': expandMenu }">item2</button>
			<button class="absolute z-[-1] w-12 h-12 bottom-[calc(8px*3)] border rounded-full transition hover:bg-zinc-100 active:bg-zinc-200" :class="{ '-translate-y-[calc(48px*3)]': expandMenu }">item3</button>
		</div>
	</div>
	<FollowingList :show-following-list="showFollowingList" @show-following-list="handleShowFollowingList" />
</template>
