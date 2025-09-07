import type { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        component: () => import('layouts/MainLayout.vue'),
        children: [
            { 
                path: '', 
                component: () => import('pages/IndexPage.vue') 
            }
            
        ],
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
        path: '/:catchAll(.*)*',
        component: () => import('pages/ErrorPages/ErrorNotFound.vue'),
    },
];

export default routes;
