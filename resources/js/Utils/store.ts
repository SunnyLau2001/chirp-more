import { reactive } from "vue";

export interface UserFollowingProps {
	id: number;
	user_id: number;
	following_id: number;
}

export type FollowingMap = Record<number, UserFollowingProps>;

export const followingState = reactive({
	/*
	The key of the map is the user_id of the chirp!
	*/
	followings: {} as FollowingMap,
	updating: true,

	async updateMap(user_id: number) {
		this.updating = true;

		try {
			const response = await window.axios.get(route("userfollowing.get_user_followings", user_id));
			if (!response || response.data.length === 0) return;

			const list = response.data as UserFollowingProps[];

			this.followings = list.reduce((map, follow) => {
				map[follow.following_id] = follow;
				return map;
			}, {} as FollowingMap);

			this.updating = false;
		} catch {
		} finally {
			this.updating = false;
		}
	},

	updateMapWithNewFollow(newFollow: UserFollowingProps) {
		this.followings[newFollow.following_id] = newFollow;
	},

	removeFollowFromMapById(removedFollowId: number) {
		delete this.followings[removedFollowId];
	},
});
