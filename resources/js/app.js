import Vue from 'vue';
import Vuetify from './vuetify'; // Import Vuetify configuration
import App from './components/App.vue';

new Vue({
    vuetify: Vuetify,
    render: h => h(App),
}).$mount('#app');
