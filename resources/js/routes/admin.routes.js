export const adminRoutes = [{
    path: '/',
    component: () => import("@/layouts/AdminLayout.vue"),
    children: [
        {
            path: '',
            name: 'dashboard',
            meta: {
                title: "Dashboard",
                auth: true
            },
            component: () => import("@/pages/Dashboard.vue"),
        },
        {
            path: 'students',
            name: 'students',
            meta: {
                title: "Students",
                auth: true
            },
            component: () => import("@/pages/Students.vue"),
        }
    ]
}]
    