import axios from 'axios';
import { router } from "./routes";
axios.defaults.baseURL = 'http://localhost:8000'

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

axios.interceptors.response.use((response) => {
    return response;
}, error => {
    if (error.response && error.response.status === 401) {
        router.push({ path: '/login' });
    }

    throw error;
})
