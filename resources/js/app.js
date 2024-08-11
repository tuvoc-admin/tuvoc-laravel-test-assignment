import Vue from 'vue';
import router  from '@/routes';

import Vuetify from '@/vuetify';

import App from '@/App.vue';

new Vue({
    el: '#app',
    router,
    vuetify: Vuetify,
    render: h => h(App),
});
