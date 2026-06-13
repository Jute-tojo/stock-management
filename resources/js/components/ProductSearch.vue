<script setup lang="ts">
import { toRef, watch } from 'vue';
import { X } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Label } from '@/components/ui/label';
import { useProductSearch } from '@/composables/useProductSearch';
import type { Product } from '@/types';

const props = defineProps<{
    products: Product[];
    modelValue: number | null;
    error?: string;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: number | null];
}>();

const {
    productSearch, showDropdown, filteredProducts, selectedProduct, selectedId,
    selectProduct, clearProduct, hideDropdown,
} = useProductSearch(toRef(() => props.products));

watch(() => props.modelValue, (id) => {
    if (id !== selectedId.value) {
        if (id === null) {
            clearProduct();
        } else {
            selectedId.value = id;
        }
    }
});
</script>

<template>
    <div class="grid gap-2">
        <Label for="product">Product</Label>

        <div v-if="!selectedProduct" class="relative">
            <Input
                id="product"
                v-model="productSearch"
                placeholder="Search product by name or SKU..."
                @focus="showDropdown = true"
                @blur="hideDropdown"
                @input="showDropdown = true"
            />
            <div
                v-if="showDropdown && filteredProducts.length > 0"
                class="absolute z-50 mt-1 max-h-48 w-full overflow-auto rounded-md border bg-popover shadow-md"
            >
                <button
                    v-for="product in filteredProducts"
                    :key="product.id"
                    type="button"
                    class="flex w-full items-center gap-2 px-3 py-2 text-left text-sm hover:bg-accent"
                    @mousedown.prevent="selectProduct(product); emit('update:modelValue', product.id)"
                >
                    <span class="font-medium">{{ product.name }}</span>
                    <span class="text-muted-foreground">{{ product.sku }}</span>
                </button>
            </div>
            <p v-if="showDropdown && productSearch && filteredProducts.length === 0" class="mt-1 text-sm text-muted-foreground">
                No products found.
            </p>
        </div>

        <div v-else class="flex items-start gap-3 rounded-lg border bg-muted/30 p-3">
            <div class="flex-1 space-y-1">
                <div class="flex items-center gap-2">
                    <span class="font-medium">{{ selectedProduct.name }}</span>
                    <Badge variant="outline">{{ selectedProduct.sku }}</Badge>
                </div>
                <div class="flex gap-4 text-sm text-muted-foreground">
                    <span>Qty: <strong>{{ selectedProduct.quantity }}</strong></span>
                    <span>Unit: <strong>{{ selectedProduct.unit }}</strong></span>
                </div>
            </div>
            <Button type="button" variant="ghost" size="icon-sm" @click="clearProduct(); emit('update:modelValue', null)">
                <X class="size-4" />
            </Button>
        </div>

        <InputError :message="error" />
    </div>
</template>
