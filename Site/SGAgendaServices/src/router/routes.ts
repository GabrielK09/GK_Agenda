import type { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        component: () => import('pages/Explorer/ExplorerURLs.vue'),
        name: 'explorer'
    },
    {
        path: '/app.gkagenda/:siteName',
        component: () => import('layouts/SiteLayout.vue'),
        children: [
            { 
                path: '', 
                component: () => import('pages/Home/HomePage.vue'),
                name: 'index'
            },
            {
                path: '/schedule/:scheduleCode',
                component: () => import('pages/Scheduling/Open/SchedulingDetails.vue'),
                name: 'schedule',
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
