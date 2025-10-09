<script setup lang="ts">
import useUser from "../composables/useUser";
import { onMounted, ref } from "vue";
import useTransaction from "@/composables/useTransaction";
import BalanceCard from "@/components/BalanceCard.vue";
import useCurrency from "@/composables/useCurrency";

const { user } = useUser();
const { transactions, fetchTransactions } = useTransaction();
const { formatCurrency } = useCurrency();

onMounted(async () => {
    await fetchTransactions();
});

</script>

<template>
    <section>
        <div class="w-full">
            <h1 class="text-2xl font-bold mb-4">Transactions</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h2 class="text-xl font-semibold mb-2">New Transfer</h2>
                    transfer form here...
                </div>
                <div>
                    <BalanceCard :balance="Number(user?.balance)"/>
                    <h2 class="text-xl font-semibold mb-2 mt-4">History</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border-collapse">
                            <thead>
                            <tr class="text-left border-b bg-gray-50 text-gray-600">
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
                                class="border-b hover:bg-gray-50 transition"
                            >
                                <td class="py-2 px-3 font-medium">
                                      <span
                                          class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold"
                                          :class="{
                                          'bg-green-100 text-green-700':  transaction.type === 'RECEIVED',
                                          'bg-red-100 text-red-700':  transaction.type === 'SENT',
                                        }"
                                      >
                                        {{ transaction.type }}
                                      </span>
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
            </div>
        </div>
    </section>
</template>
