// resources/js/axios.js

import axios from 'axios';
import store from '@/store'

const axiosInstance = axios.create({
  baseURL: `${import.meta.env.VITE_APP_URL}/api`,
  withCredentials: true, 
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    // Add any other headers you need
  },
});

/*
 * Add a response interceptor
 */
axiosInstance.interceptors.response.use(
	(response) => {
		return response
	},
	(error) => {

		let { response } = error;
		let { data, status } = response || {};

		switch (status) {
			case 419:
				store.dispatch("auth/unauthorized", null, { root: true })
				break;
			case 422:
				return {
					error: true,
					status: status,
					...data
				}
			case 500:
				console.log(response);
				break;
			default:
				return {
					error: true,
					status: status || {},
					...response
				};
		}
	}
);
export default axiosInstance;
