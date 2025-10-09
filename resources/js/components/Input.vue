<script setup lang="ts">
import { computed } from 'vue'

type InputType = 'text' | 'password' | 'email'

interface Props {
    modelValue: string
    label?: string
    type?: InputType
    placeholder?: string
    disabled?: boolean
    error?: string
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    disabled: false,
})

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()

const value = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val),
})
</script>
<template>
    <div class="flex flex-col gap-1">
        <label v-if="label" class="text-sm font-medium text-gray-700">{{ label }}</label>
        <input
            v-model="value"
            :type="type"
            :placeholder="placeholder"
            :disabled="disabled"
            class="rounded outline outline-zinc-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
        />
        <p v-if="error" class="text-sm text-red-500">{{ error }}</p>
    </div>
</template>
