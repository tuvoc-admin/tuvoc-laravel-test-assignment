import axios from '@/axios'
import { isEmpty } from "@/helpers"
import router from '@/routes';

const state = {
	user: {},
	loading: false,
	loadedOnce: false,
	token: null,
};

const getters = {
	authorizedUser: state => state.user,
	authorizedUserId: state => state.user.id,
	isLoggedIn: state => !isEmpty(state.user.id),
	currentRoute: (state, getters, rootState, rootGetters) => rootGetters('currentPath')
}

const actions = {
	async login({ commit }, formInput) {
		await axios.get("/sanctum/csrf-cookie");
		const response = await axios.post("/api/login", formInput);
		const { status, data } = response
		switch (status) {
			case 200:
				commit("LOGIN_SUCCESS", data)
				break
			case 422:
				break
			default:
		}
		return response

	},
	async getAuthUser({ commit }) {
		console.log(axios.defaults.headers.common.Authorization, 'axios');
		
		const response = await axios.get("/api/user");
		const { status, data } = response
		switch (status) {
			case 200:
				data.success ? commit("AUTH_USER_SUCCESS", data) : null
				break;
			default:
		}
		return response
	},
	async logout({ commit }) {
		const response = await axios.post("/api/logout");
		const { status } = response
		switch (status) {
			case 200:
			case 302:
				commit("LOGOUT_SUCCESS")
				break;
			default:
		}
		return response
	},
	async unauthorized({ commit }) {
		let redirect = 'login'
		commit("UNAUTHORIZED", redirect)
	},
}

const mutations = {
	'LOGIN_SUCCESS': (state, data) => {
		const { user } = data
		state.user = user
		state.loading = false
		state.loadedOnce = true;
		state.token = data.token;
		localStorage.setItem('token', data.token)
	},
	'LOGIN_ERROR': state => {
		state.status = "error";
		state.loading = false
		state.loadedOnce = true;
	},

	'AUTH_USER_SUCCESS': (state, data) => {
		const { user } = data
		if (isEmpty(state.user)) state.user = user;
		state.loading = false
	},
	'LOGOUT_SUCCESS': state => {
		state.loading = false
		state.user = {}
		localStorage.removeItem('token')
	},
	'UNAUTHORIZED': (state, redirectTo) => {
		state.user = {}
		router.push({ name: redirectTo })
	}
}

export const auth = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
