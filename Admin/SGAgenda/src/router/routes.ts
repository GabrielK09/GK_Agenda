import type { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        component: () => import('layouts/IndexLayout.vue'),        
        children: [
            { 
                path: '', 
                component: () => import('pages/IndexPage.vue'),
                name: "index",

            }            
        ],
    },
    {
        path: '/admin/:siteName/',
        component: () => import('layouts/DashboardLayout.vue'),
        name: 'app',
        children: [
            {
                path: 'dashboard',
                component: () => import('pages/App/Dashboard/Dashboard.vue'),
                name: 'dashboard'
            },
            {
                path: 'agenda',
                component: () => import('pages/App/Agenda/MainUI/Agenda.vue'),
                name: 'agenda'
            },
            {
                path: 'commission',
                component: () => import('pages/App/Attendants/Commission/AllCommissions.vue'),
                name: 'commission'
            },
            {
                path: 'services',
                component: () => import('pages/App/MyServices/ShowAllServices.vue'),
                name: 'services'
            },
            {
                path: 'attendants',
                component: () => import('pages/App/Attendants/Attendants.vue'),
                name: 'attendants'
            },
            {
                path: 'categories',
                component: () => import('pages/App/MyCategories/ShowAllCategories.vue'),
                name: 'categories'
            },
            {
                path: 'products',
                component: () => import('pages/App/MyProducts/ShowAllProducts.vue'),
                name: 'products'
            },
            {
                path: 'site',
                component: () => import('pages/App/MySite/MySite.vue'),
                name: 'site'
            }
        ]
    },
    {
        path: '/auth/',
        name: 'auth',
        component: () => import('layouts/AuthLayout.vue'),
        children: [
            {
                path: 'login',
                component: () => import('pages/Auth/Login/Login.vue')
            },
            {
                path: 'register',
                component: () => import('pages/Auth/Register/RegisterOwner.vue')
            },
            {
                path: 'complete-register',
                component: () => import('pages/Auth/Register/CompleteRegister.vue')
            },
            {
                path: 'companie-url',
                component: () => import('pages/Auth/Site/RegisterURLSite.vue')
            },
        ]
    },
    {
        path: '/:catchAll(.*)*',
        component: () => import('pages/ErrorPages/ErrorNotFound.vue'),
    },
];

export default routes;
