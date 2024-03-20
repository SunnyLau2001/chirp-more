export interface LikeProps {
	id: number;
	chirp_id: number;
	user_id: number;
	user: {
		id: number;
		name: string;
	};
}

export interface ChirpProps {
	id: number;
	user: {
		id: number;
		name: string;
	};
	user_id: number;
	message: string;
	likes: LikeProps[];
	created_at?: any;
	updated_at?: any;
}
