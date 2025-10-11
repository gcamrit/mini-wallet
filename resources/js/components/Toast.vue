<script setup lang="ts">
import { computed, ref, onMounted, type PropType } from 'vue';
import type { ToastType } from "@/composables/useToaster";

const props = defineProps({
    message: {
        type: String,
        required: true,
    },
    type: {
        type: String as PropType<ToastType>,
        default: 'info',
    },
    duration: {
        type: Number,
        default: 3000,
    }
});

const emit = defineEmits<{
    (e: 'close'): void
}>();

const isVisible = ref(true);

const typeClasses = computed(() => {
    switch (props.type) {
        case 'success':
            return 'bg-green-500';
        case 'error':
            return 'bg-red-600';
        default:
            return 'bg-green-500';
    }
});

const hideToast = () => {
    isVisible.value = false;
    emit('close');
};

onMounted(() => {
    if (props.duration > 0) {
        setTimeout(hideToast, props.duration);
    }
});
</script>
<template>
    <div
        :class="['flex items-center justify-between p-4 mb-2 rounded-lg shadow-xl text-white transform transition-all duration-300', typeClasses]"
        v-if="isVisible"
        role="alert"
    >
        <p class="mr-4 flex-grow font-medium">{{ message }}</p>

        <button
            @click="hideToast"
            class="text-2xl font-light leading-none opacity-80 hover:opacity-100 transition-opacity"
            aria-label="Close"
        >
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
        </button>
    </div>
</template>
