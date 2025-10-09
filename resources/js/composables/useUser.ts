import { ref } from 'vue';
import useApi from './useApi';
import {AxiosError} from "axios";

export interface User {
    id: number;
    name: string;
    email: string;
    balance: number;
}

const user = ref<User | null>(null);

export default function useUser() {
    const api = useApi();

    const fetchUser = async () => {
        if (user.value) {
            return;
        }
        try {
            const response = await api.get<User>('/whoami');
            user.value = response.data;
        } catch (e) {
            const err = e as AxiosError
            if (err.response?.status === 401) {
                // Not authenticated
            }
        }
    };

    const updateUserBalance = (newBalance: number) => {
        if (user.value) {
            user.value.balance = newBalance;
        }
    }

    const incrementBalance = (amount: number) => {
        if (user.value) {
            user.value.balance += amount;
        }
    }

    const decrementBalance = (amount: number) => {
        if (user.value) {
            user.value.balance -= amount;
        }
    }


    return {
        user,
        fetchUser,
        updateUserBalance,
        incrementBalance,
        decrementBalance
    };
}
