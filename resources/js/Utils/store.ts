import { reactive } from "vue";

export interface UserFollowingProps {
	id: number;
	user_id: number;
	following_id: number;
	following: {
		id: number;
		name: string;
	};
}

export const followingState = reactive({
	/*
	Key: following_id, Value: obj
	*/
	followings: new Map<number, UserFollowingProps>(),
	updating: true,

	async updateMap(user_id: number) {
		this.updating = true;

		try {
			const response = await window.axios.get(route("userfollowing.get_user_followings", user_id));
			if (!response || response.data.length === 0) return;

			const list = response.data as UserFollowingProps[];

			console.log(list);

			for (const following of list) {
				this.followings.set(following.following_id, following);
			}

			this.updating = false;
		} catch {
		} finally {
			this.updating = false;
		}
	},

	updateMapWithNewFollow(newFollow: UserFollowingProps) {
		this.followings.set(newFollow.following_id, newFollow);
	},

	removeFollowFromMapById(removedFollowId: number) {
		this.followings.delete(removedFollowId);
	},
});
