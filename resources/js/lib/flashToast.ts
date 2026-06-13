import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import type { CSSProperties } from 'vue';
import type { FlashToast } from '@/types/ui';

const toastStyles: Record<string, { style: CSSProperties }> = {
    success: {
        style: {
            background: '#16a34a',
            color: '#fff',
            border: 'none',
        },
    },
    error: {
        style: {
            background: '#dc2626',
            color: '#fff',
            border: 'none',
        },
    },
    warning: {
        style: {
            background: '#f59e0b',
            color: '#fff',
            border: 'none',
        },
    },
    info: {
        style: {
            background: '#2563eb',
            color: '#fff',
            border: 'none',
        },
    },
}

export function initializeFlashToast(): void {
    router.on('flash', (event) => {
        const flash = (event as CustomEvent).detail?.flash;
        const data = flash?.toast as FlashToast | undefined;

        if (!data) {
            return;
        }

        toast[data.type](data.message, toastStyles[data.type]);
    });
}
