export const adminRoutes = [{
    path: '/',
    component: () => import("@/layouts/AdminLayout.vue"),
    redirect: '/dashboard',
    children: [
        {
            path: 'dashboard',
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
        },
        {
            path: 'upload-session',
            name: 'upload-session',
            meta: {
                title: "Upload Session",
                auth: true
            },
            component: () => import("@/pages/UploadSession.vue"),
        }
    ]
}]
    