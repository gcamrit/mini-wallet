<script setup lang="ts">
import useUser from "../composables/useUser";
import useCurrency from "../composables/useCurrency";

const { user } = useUser();
const { formatCurrency } = useCurrency();
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
        <div v-if="user">
            <p class="mb-2">Name: {{ user.name }}</p>
            <p class="mb-2">Email: {{ user.email }}</p>
            <p class="mb-2">Balance: {{ formatCurrency(user.balance) }}</p>
        </div>
    </div>
</template>