<script setup lang="ts">
import { ref } from "vue";
import Input from "../components/Input.vue";
import useAuth from "../composables/useAuth.js";

const { register } = useAuth();

const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const errors = ref(null);

const handleRegister = async () => {
    try {
        await register({
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value,
        })
    } catch (e) {
        if (e.response?.data?.errors) {
            errors.value = e.response.data.errors
        }
    }
}
</script>

<template>
    <div class="flex flex-col items-center min-w-8xl ">
        <h1 class="text-blue-600">Register Page</h1>

        <form @submit.prevent="handleRegister" class="space-y-4">
            <Input type="text" label="Full Name" v-model="name" :error="errors?.name" />
            <Input type="email" label="Email" v-model="email" :error="errors?.email" />
            <Input type="password" label="Password" v-model="password" :error="errors?.password" />
            <Input type="password" label="Password Confirm" v-model="password_confirmation" />
            <div>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</template>
