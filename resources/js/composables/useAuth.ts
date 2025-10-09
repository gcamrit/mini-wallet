import { useRouter } from 'vue-router';
import useUser from './useUser';
import axios from "axios";

export default function useAuth() {
    const router = useRouter();
    const { fetchUser, user } = useUser();

    const login = async (credentials: object) => {
        try {
            await axios.post('/login', credentials);
            await fetchUser();
            await router.push('/');
        } catch (e) {
            throw e;
        }
    };

    const register = async (data: object) => {
        try {
            await axios.post('/register', data);
            await fetchUser();
            await router.push('/');
        } catch (e) {
            throw e;
        }
    };

    const logout = async () => {
        try {
            await axios.post('/logout');
            user.value = null;
            await router.push('/login');
        } catch (e) {
            console.error('Failed to logout', e);
        }
    };

    return {
        login,
        register,
        logout,
    };
}
