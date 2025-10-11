<script setup lang="ts">
import useUser from "../composables/useUser";
import {onMounted} from "vue";
import useTransaction from "@/composables/useTransaction";
import BalanceCard from "@/components/BalanceCard.vue";
import useCurrency from "@/composables/useCurrency";
import TransactionType from "@/components/TransactionType.vue";

const {user} = useUser();
const {transactions, fetchTransactions, initializeTransactionListener} = useTransaction();
const {formatCurrency} = useCurrency();

initializeTransactionListener()

onMounted(() => {
    fetchTransactions();
});
</script>

<template>
    <section>
        <div class="w-full space-y-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold mb-4">Transactions</h1>
                <router-link
                    to="/transactions/transfer"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    New Transfer
                </router-link>
            </div>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold mb-2 mt-4">History</h2>
                <BalanceCard :balance="Number(user?.balance)"/>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-200 rounded-lg">
                    <thead>
                    <tr class="text-left border-b border-gray-200 bg-gray-50 text-gray-600">
                        <th class="py-2 px-3 font-medium">Type</th>
                        <th class="py-2 px-3 font-medium">Party</th>
                        <th class="py-2 px-3 font-medium text-right">Amount</th>
                        <th class="py-2 px-3 font-medium text-right">Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="transaction in transactions"
                        :key="transaction.id"
                        class="border-b border-gray-200 hover:bg-gray-50 transition"
                    >
                        <td class="py-2 px-3 font-medium">
                            <TransactionType :type="transaction.type" />
                        </td>

                        <td class="py-2 px-3 text-gray-800">
                            {{ transaction.party.name }}
                        </td>

                        <td
                            class="py-2 px-3 text-right font-semibold"
                            :class="{
                                    'text-green-600': transaction.type === 'RECEIVED',
                                    'text-red-600': transaction.type === 'SENT',
                                  }"
                        >
                            {{ formatCurrency(transaction.amount) }}
                        </td>

                        <td class="py-2 px-3 text-right text-gray-500">
                            {{ new Date(transaction.created_at).toLocaleDateString() }}
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div
                    v-if="!transactions.length"
                    class="text-center py-6 text-gray-500"
                >
                    No transactions found.
                </div>
            </div>
        </div>
    </section>
</template>
