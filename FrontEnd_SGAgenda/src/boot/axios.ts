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
            const token = LocalStorage.getItem("authToken");

            if (!token) {
                router.replace({
                    path: '/login'
                });
            };

            return config;
        }
    )

    app.config.globalProperties.$axios = axios;
  
    app.config.globalProperties.$api = api;
  
});

export { api };