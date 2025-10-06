<script setup lang="ts">
import { ref } from "vue";
import Input from "../components/Input.vue";
import useAuth from "../composables/useAuth";

const { login } = useAuth();

const email = ref('')
const password = ref('')
const error = ref<string | null>(null)

const handleLogin = async () => {
    try {
        await login({ email: email.value, password: password.value });
    } catch (e: any) {
        if (e.response?.data?.message) {
            error.value = e.response.data.message;
        }
    }
}
</script>

<template>
    <div class="flex flex-col items-center min-w-8xl ">
        <h1 class="text-blue-600">Login Page</h1>

        <form @submit.prevent="handleLogin" class="space-y-4">
            <Input type="email" label="Email" v-model="email" :error="error" />
            <Input type="password" label="Password" v-model="password" />
            <div>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</template>
