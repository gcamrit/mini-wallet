<script setup>
import useUser from "./composables/useUser";
import useAuth from "./composables/useAuth";
import useTransaction from "./composables/useTransaction";
import BalanceCard from "@/components/BalanceCard.vue";
import {watch} from "vue";
import {useToast} from "@/composables/useToaster.js";
import Toast from "@/components/Toast.vue";

const {user} = useUser();
const {logout} = useAuth();
const {initializeTransactionListener} = useTransaction();
const { toasts, removeToast } = useToast();

watch(user, () => {
    initializeTransactionListener()
}, {immediate: true})
</script>
<template>
    <div class="min-h-screen bg-gray-100">
        <header class="bg-white shadow">
            <div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900">Mini Wallet</h1>
                    <nav class="flex gap-4">
                        <template v-if="user">
                            <RouterLink to="/" class="text-gray-500 hover:text-gray-700">Dashboard</RouterLink>
                            <RouterLink to="/transactions" class="text-gray-500 hover:text-gray-700">Transactions
                            </RouterLink>
                        </template>
                        <RouterLink v-if="!user" to="/register" class="text-gray-500 hover:text-gray-700">Register
                        </RouterLink>
                        <RouterLink v-if="!user" to="/login" class="text-gray-500 hover:text-gray-700">Login
                        </RouterLink>
                        <button v-if="user" @click="logout" class="text-gray-500 hover:text-gray-700">Logout</button>
                    </nav>
                    <template v-if="user">
                        <BalanceCard :balance="Number(user.balance)"/>
                    </template>
                </div>
            </div>
        </header>
        <main>
            <div class="container mx-auto py-6 sm:px-6 lg:px-8">
                <RouterView/>
            </div>
        </main>
    </div>
    <Teleport to="body">
        <div class="fixed bottom-0 right-0">
            <Toast v-for="toast in toasts" :message="toast.message" :duration="toast.duration" :key="toast.id" :type="toast.type" @close="removeToast(toast.id)" />
        </div>
    </Teleport>
</template>
