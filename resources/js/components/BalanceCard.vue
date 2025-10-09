<script setup lang="ts">
import useCurrency from "@/composables/useCurrency";

defineProps<{
    balance: number;
}>();

const { formatCurrency } = useCurrency();
</script>

<template>
    <div>
        <h2 class="text-xl font-semibold mb-2">Balance</h2>
        <p class="text-lg">{{ formatCurrency(balance) }}</p>
    </div>
</template>
