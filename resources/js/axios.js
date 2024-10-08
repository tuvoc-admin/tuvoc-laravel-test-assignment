// resources/js/axios.js

import axios from 'axios';
import store from '@/store'
const token = localStorage.getItem('token')
const axiosInstance = axios.create({
  baseURL: `${import.meta.env.VITE_APP_URL}`,
  withCredentials: true, 
  withXSRFToken: true, 
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Content-Type': 'multipart/form-data; charset=UTF-8',
	'Accept': 'application/json',
	'Authorization': `Bearer ${token}`,
  },
});

/*
 * Add a response interceptor
 */
axiosInstance.interceptors.response.use(
	(response) => {
		let { data, status } = response || {};
		if(status === 200){
			if(data?.token){
				updateToken(data.token)
			}else if(data?.logout){
				updateToken(null)
			}
		}
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

function updateToken(token){
	axiosInstance.defaults.headers.Authorization = `Bearer ${token}`;
}
export default axiosInstance;
