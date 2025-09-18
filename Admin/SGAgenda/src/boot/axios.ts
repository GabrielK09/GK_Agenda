import { defineBoot } from '#q-app/wrappers';
import axios, { type AxiosInstance } from 'axios';
import { LocalStorage } from 'quasar';

axios.defaults.withCredentials = false;
declare module 'vue' {
    interface ComponentCustomProperties {
        $axios: AxiosInstance;
        $api: AxiosInstance;
    }
}

const api = axios.create({ baseURL: process.env.API_URL });

export default defineBoot(({ app, router }) => {
    api.interceptors.request.use(
        (config) => {
            const publicRoutes: string[] = [
                '/site/create-url',
                '/auth/register-owner',
                '/auth/update-owner',
                '/auth/login',

            ];

            const isPublic = publicRoutes.some(route => config.url?.includes(route));

            const token = LocalStorage.getItem("authToken");

            if (!token && !isPublic) {
                app.config.globalProperties.$q.notify({
                    color: 'red',
                    message: 'O usuário não está logado!',
                    position: 'top',
                    timeout: 2000
                    
                });

                router.replace({
                    path: '/auth/login'
                });
            };

            if (token)
            {
                config.headers.Authorization = `Bearer ${token}`;
            };

            return config;
        }
    );

    api.interceptors.response.use(
        (response) => response,
        (error) => {
            if (error.status === 401) {
                app.config.globalProperties.$q.notify({
                    color: 'red',
                    message: 'O usuário não está logado!',
                    position: 'top',
                    timeout: 2000
                    
                });

                router.replace({
                    path: '/auth/login'
                });
            };
            
            return Promise.reject(error);
        }
    );

    app.config.globalProperties.$axios = axios;
  
    app.config.globalProperties.$api = api;
  
});

export { api };