<template>
    <input
        type="number"
        v-model="amount"
        step="0.01"
        class="rounded outline outline-zinc-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
        placeholder="Enter amount"
        @blur="format"
    />
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

const props = defineProps<{
    modelValue: number | null;
}>();

const emit = defineEmits(['update:modelValue']);

const amount = ref<any>(props.modelValue ?? '');

watch(amount, (val) => {
    const parsed = parseFloat(val);
    emit('update:modelValue', isNaN(parsed) ? null : parsed);
});

watch(
    () => props.modelValue,
    (val) => {
        if (val !== parseFloat(amount.value)) {
            amount.value = val ?? '';
        }
    }
);

function format() {
    const parsed = parseFloat(amount.value);
    if (!isNaN(parsed)) {
        amount.value = parsed.toFixed(2);
    } else {
        amount.value = '';
    }
}
</script>
