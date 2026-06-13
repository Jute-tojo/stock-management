<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Search, Plus, Pencil, Trash2, PackageIcon, Tags } from '@lucide/vue';
import { useDebounceFn } from '@vueuse/core';
import { watch, ref } from 'vue';
import { index as productIndex } from '@/routes/products';
import CategoryDialog from '@/components/CategoryDialog.vue';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import ProductDialog from '@/components/ProductDialog.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Dialog, DialogContent,
} from '@/components/ui/dialog';
import { useProductForm } from '@/composables/useProductForm';
import type { ProductPaginate, Category } from '@/types';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Products', href: '/products' },
        ],
    },
});

const props = defineProps<{
    products: ProductPaginate;
    categories: Category[];
    units: Array<{ value: string; label: string }>;
    filters: string | null;
}>();

const search = ref(props.filters ?? '');

const debouncedSearch = useDebounceFn((value: string) => {
    router.get(productIndex.url({ query: { search: value || null } }), {}, {
        preserveState: true,
        replace: true,
    });
}, 3000);

watch(search, (val) => {
    debouncedSearch(val);
});

const dialogRef = ref<InstanceType<typeof ProductDialog>>();
const categoryDialogOpen = ref(false);

const previewImage = ref<string | null>(null);

const { destroy } = useProductForm();
</script>

<template>
    <Head title="Products" />

    <div class="flex flex-col gap-6 p-4">
        <div class="flex items-center justify-between">
            <Heading title="Products" description="Manage your product catalog" />

            <Button @click="dialogRef?.openCreateModal()">
                <Plus class="size-4" />
                New Product
            </Button>
        </div>

        <div class="flex items-center gap-2">
            <div class="relative w-full max-w-sm">
                <Search class="text-muted-foreground absolute top-1/2 left-3 size-4 -translate-y-1/2" />
                <Input
                    v-model="search"
                    placeholder="Search by name or SKU..."
                    class="pl-9"
                />
            </div>
            <Button variant="outline" size="sm" @click="categoryDialogOpen = true">
                <Tags class="size-4" />
                Categories
            </Button>
        </div>

        <div class="rounded-lg border">
            <table class="w-full">
                <thead>
                    <tr class="border-b bg-muted/50">
                        <th class="px-4 py-3 text-left text-sm font-medium">Image</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Name</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">SKU</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Category</th>
                        <th class="px-4 py-3 text-right text-sm font-medium">Price</th>
                        <th class="px-4 py-3 text-right text-sm font-medium">Qty</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Unit</th>
                        <th class="px-4 py-3 text-right text-sm font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="product in products.data"
                        :key="product.id"
                        class="border-b last:border-b-0 hover:bg-muted/50"
                    >
                        <td class="px-4 py-3">
                            <button
                                v-if="product.image_url"
                                type="button"
                                class="cursor-pointer"
                                @click="previewImage = product.image_url"
                            >
                                <img
                                    :src="product.image_url"
                                    alt="Product"
                                    class="size-10 rounded-md object-contain"
                                />
                            </button>
                            <PackageIcon v-else class="size-10 text-muted-foreground" />
                        </td>
                        <td class="px-4 py-3 text-sm">{{ product.name }}</td>
                        <td class="px-4 py-3 text-sm text-muted-foreground">{{ product.sku }}</td>
                        <td class="px-4 py-3 text-sm">{{ product.category?.name ?? '-' }}</td>
                        <td class="px-4 py-3 text-right text-sm">{{ Number(product.price).toFixed(2) }}</td>
                        <td class="px-4 py-3 text-right text-sm">{{ product.quantity }}</td>
                        <td class="px-4 py-3 text-sm">{{ product.unit_label }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <Button variant="ghost" size="icon-sm" @click="dialogRef?.openEditModal(product)">
                                    <Pencil class="size-4" />
                                </Button>
                                <Button variant="ghost" size="icon-sm" @click="destroy(product)">
                                    <Trash2 class="size-4 text-destructive" />
                                </Button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="products.data.length === 0">
                        <td colspan="8" class="px-4 py-8 text-center text-sm text-muted-foreground">
                            No products found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination
            :current_page="products.current_page"
            :last_page="products.last_page"
            :per_page="products.per_page"
            :total="products.total"
        />
    </div>

    <ProductDialog
        ref="dialogRef"
        :categories="categories"
        :units="units"
        @open-categories="categoryDialogOpen = true"
    />

    <CategoryDialog
        v-if="categoryDialogOpen"
        :categories="categories"
        @close="categoryDialogOpen = false"
    />

    <Dialog :open="!!previewImage" @update:open="previewImage = null">
        <DialogContent class="sm:max-w-lg">
            <img
                v-if="previewImage"
                :src="previewImage"
                alt="Product image"
                class="max-h-[80vh] w-full rounded-md object-contain"
            />
        </DialogContent>
    </Dialog>
</template>
