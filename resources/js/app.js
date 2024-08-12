import Vue from 'vue';
import router from '@/routes';
import store from "@/store"

import vuetify from '@/vuetify';
import App from '@/App.vue';

new Vue({
    router,
    store,
    vuetify,
    render: h => h(App),
}).$mount('#app');
