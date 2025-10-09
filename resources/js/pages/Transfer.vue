<script setup lang="ts">
import { ref } from "vue";
import useTransaction from "@/composables/useTransaction";
import TransferForm from "@/components/TransferForm.vue";
import {useRouter} from "vue-router";
import {useToast} from "@/composables/useToaster";

const { success, error } = useToast();

const router = useRouter();

const { createTransaction } = useTransaction();
const form = ref({
    recipient_id: "",
    amount: "",
});
const errors = ref({});

const sendTransfer = async (formData: { recipient_id: string; amount: number }) => {
    try {
        await createTransaction(formData.recipient_id, formData.amount);
        form.value = {recipient_id: "", amount: ""};
        success("Transfer successfull.")
        await router.push({path: "/transactions"})
    } catch (err) {
        if (err.response?.data?.errors) {
            errors.value = err.response.data.errors
        }
        error("Oops, Something went wrong!!!")
        console.error("Transfer failed:", err);
    }
};
</script>

<template>
    <section>
        <div class="w-full">
            <h1 class="text-2xl font-bold mb-4">New Transfer</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <TransferForm @submit="sendTransfer" :errors="errors"/>
                </div>
            </div>
        </div>
    </section>
</template>
