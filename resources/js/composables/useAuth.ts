import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

interface User {
    name: string;
    email: string;
}

export default function useAuth() {
    const user = ref<User | null>(null);
    const router = useRouter();

    const login = async (credentials: object) => {

        try {
            const response = await axios.post('/api/login', credentials);
            await router.push('/');
        } catch (e) {
            throw e;
        }
    };

    const register = async (data: object) => {
        try {
            await axios.post('/api/register', data);
            await router.push('/');
        } catch (e) {
            throw e;
        }
    };

    const logout = async () => {
        try {
            await axios.post('/api/logout');
            user.value = null;
            await router.push('/login');
        } catch (e) {
            console.error('Failed to logout', e);
        }
    };

    const getUser = async () => {
        try {
            const response = await axios.get('/api/whoami');
            user.value = response.data;
        } catch (e) {
            if (e.response?.status === 401) {
                // Not authenticated
            }
        }
    };

    return {
        user,
        login,
        register,
        logout,
        getUser,
    };
}
