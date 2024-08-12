import Vue from "vue"
import Vuex from "vuex"

import * as modules from "@/store/modules"

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        currentPath: "",
    },
    getters: {
    },
    mutations: {
    },
    actions: {

    },
    modules
});

export default store;