import axios from 'axios';
import { router } from "@/routes";

const api = axios.create({
    baseURL: '/api',
});

api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response && error.response.status === 401) {
            router.push({ path: '/login' });
        }
        return Promise.reject(error);
    }
);

export default function useApi() {
    return api;
}
