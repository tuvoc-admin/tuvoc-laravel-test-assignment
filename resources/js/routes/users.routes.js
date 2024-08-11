export const userRoutes = [
    {
        path: 'users',
        name: 'users',
        meta: {
            title: "Users",
            auth: true
        },
        component: () => import("@/pages/Users.vue"),
    },
]