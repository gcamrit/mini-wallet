import axios from 'axios';
import { router } from "./routes";
import { configureEcho } from "@laravel/echo-vue";


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

configureEcho({
    broadcaster: 'pusher',
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    encrypted: true,
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                axios.post('api/broadcasting/auth', {
                    socket_id: socketId,
                    channel_name: channel.name
                }).then(response => {
                    callback(false, response.data);
                }).catch(error => {
                    callback(true, error);
                });
            }
        };
    },
});
