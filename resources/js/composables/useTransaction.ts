import {ref} from "vue";
import useApi from "./useApi";
import {useEcho} from "@laravel/echo-vue";
import useUser from "./useUser";

export interface TransactionParty {
    id: number;
    name: string;
}

export type TransactionType = "SENT" | "RECEIVED";

export interface Transaction {
    id: number;
    amount: number;
    type: TransactionType;
    party: TransactionParty;
    created_at: string;
}

export interface TransferSuccessfulEvent {
    transaction: {
        id: number;
        amount: number;
        commission_amount: number
        sender: TransactionParty;
        receiver: TransactionParty;
        created_at: string;
    };
}

const transactions = ref<Transaction[]>([]);

export default function useTransaction() {

    const api = useApi();
    const {user, incrementBalance, decrementBalance} = useUser();


    const fetchTransactions = async () => {
        try {
            const response = await api.get<{ data: Transaction[] }>("/transactions");
            transactions.value = response.data.data;
        } catch (error) {
            console.error("Failed to fetch transactions:", error);
        }
    };

    const createTransaction = async (
        recipient_id: string | number,
        amount: string | number
    ) => {
        try {
            await api.post<Transaction>("/transactions", {
                recipient_id,
                amount,
            });
        } catch (error) {
            console.error("Failed to create transaction:", error);
            throw error;
        }
    };

    const initializeTransactionListener = () => {
        if (!user.value) return;
        useEcho<TransferSuccessfulEvent>(
            `user.${user.value?.id}.transactions`,
            "TransferSuccessful",
            (e) => {
                console.log(e.transaction)
                const exist = transactions.value.find((t) => t.id === e.transaction.id)
                const t = {
                    id: e.transaction.id,
                    type: e.transaction.sender.id == user.value?.id ? "SENT" : "RECEIVED" as TransactionType,
                    amount: e.transaction.amount,
                    party: e.transaction.sender.id == user.value?.id ? e.transaction.receiver : e.transaction.sender,
                    created_at: e.transaction.created_at,
                } as Transaction;

                if(!exist) {
                    transactions.value.unshift(t);
                }
                if (user.value) {
                    switch (t.type) {
                        case "RECEIVED":
                            incrementBalance(Number(e.transaction.amount));
                            break;
                        case "SENT":
                            decrementBalance(Number(e.transaction.amount) + Number(e.transaction.commission_amount));
                            break;
                    }
                }
            }
        );
    };

    return {
        transactions,
        fetchTransactions,
        createTransaction,
        initializeTransactionListener,
    };
}
