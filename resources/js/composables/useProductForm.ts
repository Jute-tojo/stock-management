import { useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import type { Product, ProductFormData } from '@/types';

const emptyForm: ProductFormData = {
    category_id: null,
    name: '',
    sku: '',
    image: null,
    description: '',
    price: 0,
    quantity: 0,
    unit: 'pcs',
};

export const useProductForm = () => {
    const isOpen = ref(false);
    const isEdit = ref(false);
    const editingProduct = ref<Product | null>(null);

    const form = useForm<ProductFormData>({ ...emptyForm });

    const openCreateModal = () => {
        isEdit.value = false;
        editingProduct.value = null;
        form.reset();
        form.clearErrors();
        isOpen.value = true;
    };

    const openEditModal = (product: Product) => {
        isEdit.value = true;
        editingProduct.value = product;
        form.reset();
        form.clearErrors();
        form.category_id = product.category?.id ?? null;
        form.name = product.name;
        form.sku = product.sku;
        form.image = null;
        form.description = product.description ?? '';
        form.price = product.price;
        form.quantity = product.quantity;
        form.unit = product.unit;
        isOpen.value = true;
    };

    const closeModal = () => {
        isOpen.value = false;
        form.reset();
        form.clearErrors();
    };

    const submit = () => {
        if (isEdit.value && editingProduct.value) {
            form
                .transform((data) => ({ ...data, _method: 'PATCH' }))
                .post(`/products/${editingProduct.value.id}`, {
                    preserveScroll: true,
                    onSuccess: () => closeModal(),
                    onFinish: () => form.transform((data) => data),
                });
        } else {
            form.post('/products', {
                preserveScroll: true,
                onSuccess: () => closeModal(),
            });
        }
    };

    const destroy = (product: Product) => {
        router.delete(`/products/${product.id}`, {
            preserveScroll: true,
        });
    };

    return {
        form,
        isOpen,
        isEdit,
        editingProduct,
        openCreateModal,
        openEditModal,
        closeModal,
        submit,
        destroy,
    };
};
