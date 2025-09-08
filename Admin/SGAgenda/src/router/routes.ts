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
                component: () => import('pages/App/Dashboard/Dashboard.vue'),
                name: 'agenda'
            },
            {
                path: 'services',
                component: () => import('pages/App/MyServices/ShowAllServices.vue'),
                name: 'services'
            },

        ]
    },
    {
        path: '/login',
        component: () => import('pages/Auth/Login/Login.vue')
    },
    {
        path: '/register',
        component: () => import('pages/Auth/Register/RegisterOwner.vue')
    },
    {
        path: '/complete-register',
        component: () => import('pages/Auth/Register/CompleteRegister.vue')
    },
    {
        path: '/companie-url',
        component: () => import('pages/Auth/Site/RegisterURLSite.vue')
    },
    {
        path: '/:catchAll(.*)*',
        component: () => import('pages/ErrorPages/ErrorNotFound.vue'),
    },
];

export default routes;
