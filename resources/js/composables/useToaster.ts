import { reactive, readonly } from 'vue';


export type ToastType = 'success' | 'error';

export interface Toast {
    id: number;
    message: string;
    type: ToastType;
    duration: number; // in milliseconds
}

export interface ToastOptions {
    message: string;
    type?: ToastType;
    duration?: number;
}

const toasts: Toast[] = reactive([]);
let idCounter = 0;

const addToast = (options: ToastOptions) => {
    const id = idCounter++;
    const type: ToastType = options.type || 'success';
    const newToast: Toast = {
        id,
        message: options.message,
        type: type,
        duration: options.duration !== undefined ? options.duration : 3000,
    };
    toasts.push(newToast);
};

const removeToast = (id: number) => {
    const index = toasts.findIndex(t => t.id === id);
    if (index !== -1) {
        toasts.splice(index, 1);
    }
};

const success = (message: string, duration?: number) => addToast({ message, type: 'success', duration });
const error = (message: string, duration?: number) => addToast({ message, type: 'error', duration });

export function useToast() {
    return {
        toasts: readonly(toasts),
        addToast,
        removeToast,
        success,
        error,
    };
}
