import type { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
    {
        path: '/app.gkagenda/:siteName',
        component: () => import('layouts/SiteLayout.vue'),
        children: [
            { 
                path: '', 
                component: () => import('pages/Home/HomePage.vue'),
                name: 'index'
            }
        ],
    },

    // Always leave this as last one,
    // but you can also remove it
    {
        path: '/:catchAll(.*)*',
        component: () => import('src/pages/ErrorPage/ErrorNotFound.vue'),
    },
];

export default routes;
