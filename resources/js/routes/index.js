import Vue from 'vue'
import VueRouter from 'vue-router'
import { adminRoutes } from '@/routes/admin.routes'
import store from '@/store'

Vue.use(VueRouter)

const routes = [
    {
        path: '/login',
        component: () => import("@/layouts/LoginLayout.vue"),
        children: [
            {
                path: '',
                name: 'login',
                meta: {
                    title: "Login",
                    auth: true
                },
                component: () => import("@/pages/auth/Login.vue"),
            },
        ]
    },
    ...adminRoutes,
]

const router = new VueRouter({
    mode: 'history',
    routes,
    scrollBehavior() {
        return { x: 0, y: 0 }
    }
});

router.beforeEach(async (to, from, next) => {
    if (to.matched.some(record => record.meta.auth)) {
        const res = await store.dispatch('auth/getAuthUser', null, { root: true });
        // if (res?.status !== 200) {
        //     let redirect = {
        //         name: 'login',
        //     }
        //     next(redirect)
        //     return;
        // }
    }
    next();
});

export default router