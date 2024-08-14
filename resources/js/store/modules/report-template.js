import axios from '@/axios'
import { isEmpty, addNewFilter } from "@/helpers"

const state = {
	loadedOnce: false,
	reportTemplate: null
};

const getters = {
}

const actions = {
	async getTemplate(ctx, formInput) {
		return axios.get("/api/report-template");
	},
	async getStudents({ commit, state }) {
		if(state.loadedOnce) return false;
		const response = axios.get("/api/report-template");
		const { status, data } = response
		switch (status) {
			case 200:
				data.success ? commit("SET_REPORT_TEMPLATE", data) : null
				break;
			default:
		}
		return response
	},
	async upadteReportTemplate(ctx, formInput) {
		return axios.post("/api/report-template", formInput);
	},
}

const mutations = {
	"SET_REPORT_TEMPLATE": (state, data){
		state.reportTemplate = data.template
		state.loadedOnce = true
	}
}

export const reportTemplate = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
