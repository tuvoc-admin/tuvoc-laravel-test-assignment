import axios from '@/axios'
import { isEmpty, addNewFilter } from "@/helpers"

const state = {
};

const getters = {
}

const actions = {
	async uploadToParceDocx(ctx, formInput) {
		return axios.post("/api/parceDocx", formInput);
	},
}

const mutations = {
}

export const uploadSession = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
