<script setup lang="ts">
import { ref, defineProps, defineEmits } from 'vue';
import Input from './Input.vue';
import AmountInput from "@/components/AmountInput.vue";

interface TransferForm {
  recipient_id: string;
  amount: number;
}
interface Props {
    errors?: {
        recipient_id?: string[];
        amount?: string[];
    };
}
const props = defineProps<Props>();

const emit = defineEmits<{
  (event: 'submit', form: TransferForm): void;
}>();

const form = ref<TransferForm>({
  recipient_id: '',
  amount: 0.00
});

const submitForm = () => {
  emit('submit', form.value);
};
</script>

<template>
    <form @submit.prevent="submitForm" class="flex flex-col gap-4">
        <Input
            v-model="form.recipient_id"
            type="text"
            label="Recipient ID"
            :error="props.errors?.recipient_id?.[0]"
        />
        <AmountInput
            currency="USD"
            v-model="form.amount"
            type="number"
            label="Amount"
            :error="props.errors?.amount?.[0]"
        />
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">
            Send Transfer
        </button>
    </form>
</template>
