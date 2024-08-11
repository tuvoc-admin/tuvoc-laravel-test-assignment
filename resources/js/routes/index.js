import Vue from 'vue'
import VueRouter from 'vue-router'
import { userRoutes } from '@/routes/users.routes.js'
Vue.use(VueRouter)

const routes = [
    ...userRoutes,
    {
        path: '',
        name: 'dashboard',
        meta: {
            title: "Dashboard",
            auth: true
        },
        component: () => import("@/pages/Dashboard.vue"),
    },
]

const router = new VueRouter({
    mode: 'history',
    routes,
    scrollBehavior() {
        return { x: 0, y: 0 }
    }
});

router.beforeEach(async (to, from, next) => {
});

export default router