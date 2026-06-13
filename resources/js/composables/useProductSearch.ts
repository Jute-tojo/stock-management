import { computed, ref } from 'vue';
import type { Ref } from 'vue';
import type { Product } from '@/types';

export const useProductSearch = (products: Ref<Product[]>) => {
    const productSearch = ref('');
    const showDropdown = ref(false);
    const selectedId = ref<number | null>(null);

    const filteredProducts = computed(() => {
        if (!productSearch.value) return products.value;

        const q = productSearch.value.toLowerCase();

        return products.value.filter(
            (p) => p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q),
        );
    });

    const selectedProduct = computed(() => {
        if (!selectedId.value) return null;

        return products.value.find((p) => p.id === selectedId.value) ?? null;
    });

    const selectProduct = (product: Product) => {
        selectedId.value = product.id;
        productSearch.value = '';
        showDropdown.value = false;
    };

    const clearProduct = () => {
        selectedId.value = null;
        productSearch.value = '';
    };

    const hideDropdown = () => {
        setTimeout(() => {
 showDropdown.value = false; 
}, 200);
    };

    return {
        productSearch,
        showDropdown,
        filteredProducts,
        selectedProduct,
        selectedId,
        selectProduct,
        clearProduct,
        hideDropdown,
    };
};
