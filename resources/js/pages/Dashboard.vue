<script setup lang="ts">
import useAuth from "../composables/useAuth";
import { onMounted } from "vue";

const { user, getUser, logout } = useAuth();

onMounted(async () => {
    await getUser();
});
</script>

<template>
    <div>
        <h1>Dashboard</h1>
        <div v-if="user">
            <p>Name: {{ user.name }}</p>
            <p>Email: {{ user.email }}</p>
            <button @click="logout">Logout</button>
        </div>
    </div>
</template>
