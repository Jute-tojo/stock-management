import { useForm } from '@inertiajs/vue3';
import { store as storeRoute } from '@/routes/stock-movements';
import type { StockMovementFormData } from '@/types';

const emptyForm: StockMovementFormData = {
    product_id: null,
    type: 'in',
    quantity: 1,
    notes: '',
};

export const useStockMovementForm = () => {
    const form = useForm<StockMovementFormData>({ ...emptyForm });

    const resetForm = () => {
        form.reset();
        form.clearErrors();
    };

    const submit = () => {
        form.post(storeRoute.url(), {
            preserveScroll: true,
            onSuccess: () => resetForm(),
        });
    };

    return { form, resetForm, submit };
};
