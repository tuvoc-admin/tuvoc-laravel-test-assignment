import axios from '@/axios'
import { isEmpty, addNewFilter } from "@/helpers"

const state = {
	students: [],
	loadedOnce: false
};

const getters = {
	students: state => state.students,
}

const actions = {
	async createStudent({ commit }, formInput) {
		const response = await axios.post("/students", formInput);
		const { status, data } = response
		switch (status) {
			case 200:
				commit("CREATE_SUCCESS", data)
				break
			case 422:
				break
			default:
		}
		return response

	},
	async getStudents({ commit }) {
		const response = await axios.get("/students");
		const { status, data } = response
		switch (status) {
			case 200:
				data.success ? commit("LIST_SUCCESS", data) : null
				break;
			default:
		}
		return response
	},
}

const mutations = {
	'CREATE_SUCCESS': (state, data) => {
		if(!isEmpty(data.student)){
			state.students = addNewFilter(state.students, [data.student])
		}
	},
	'LIST_SUCCESS': (state, data) => {
		if(!isEmpty(data.students)){
			state.students = addNewFilter(state.students, data.students)
		}
		state.loadedOnce = true;
	},
}

export const student = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
