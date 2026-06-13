<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Search, Plus, ArrowDown, ArrowUp } from '@lucide/vue';
import { useDebounceFn } from '@vueuse/core';
import { ref, watch } from 'vue';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import {
    Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select';
import { index as movementIndex } from '@/routes/stock-movements';
import { useStockMovementForm } from '@/composables/useStockMovementForm';
import type { StockMovementPaginate, Product } from '@/types';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Stock Movements', href: '/stock-movements' },
        ],
    },
});

const props = defineProps<{
    movements: StockMovementPaginate;
    products: Product[];
    filters: string | null;
}>();

const activeTab = ref<'form' | 'history'>('form');

const search = ref(props.filters ?? '');

const debouncedSearch = useDebounceFn((value: string) => {
    router.get(movementIndex.url({ query: { search: value || null } }), {}, {
        preserveState: true,
        replace: true,
    });
}, 3000);

watch(search, (val) => {
    debouncedSearch(val);
});

const { form, submit } = useStockMovementForm();

const badgeVariant = (type: string) => {
    switch (type) {
        case 'in': return 'default' as const;
        case 'out': return 'destructive' as const;
        case 'initial': return 'secondary' as const;
        default: return 'outline' as const;
    }
};

const typeLabel = (type: string) => {
    switch (type) {
        case 'in': return 'In';
        case 'out': return 'Out';
        case 'initial': return 'Initial';
        default: return type;
    }
};
</script>

<template>
    <Head title="Stock Movements" />

    <div class="flex flex-col gap-6 p-4">
        <Heading title="Stock" description="Manage inventory movements" />

        <div class="flex gap-1 rounded-lg border bg-muted/50 p-1">
            <Button
                variant="ghost"
                :class="activeTab === 'form' ? 'bg-background shadow-sm' : ''"
                class="flex-1"
                @click="activeTab = 'form'"
            >
                <Plus class="size-4" />
                Add Movement
            </Button>
            <Button
                variant="ghost"
                :class="activeTab === 'history' ? 'bg-background shadow-sm' : ''"
                class="flex-1"
                @click="activeTab = 'history'"
            >
                <Search class="size-4" />
                History
            </Button>
        </div>

        <div v-if="activeTab === 'form'" class="rounded-lg border bg-card p-6">
            <h3 class="mb-4 text-lg font-semibold">New Stock Movement</h3>
            <form @submit.prevent="submit" class="space-y-4">
                <div class="grid gap-2">
                    <Label for="product">Product</Label>
                    <Select v-model="form.product_id">
                        <SelectTrigger id="product">
                            <SelectValue placeholder="Select a product" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="product in products" :key="product.id" :value="product.id">
                                {{ product.name }} ({{ product.sku }})
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.product_id" />
                </div>

                <div class="grid gap-2">
                    <Label>Type</Label>
                    <div class="flex gap-2">
                        <Button
                            type="button"
                            variant="outline"
                            :class="form.type === 'in' ? 'border-green-500 bg-green-50 text-green-700 dark:bg-green-950 dark:text-green-400' : ''"
                            class="flex-1"
                            @click="form.type = 'in'"
                        >
                            <ArrowDown class="size-4" />
                            In
                        </Button>
                        <Button
                            type="button"
                            variant="outline"
                            :class="form.type === 'out' ? 'border-red-500 bg-red-50 text-red-700 dark:bg-red-950 dark:text-red-400' : ''"
                            class="flex-1"
                            @click="form.type = 'out'"
                        >
                            <ArrowUp class="size-4" />
                            Out
                        </Button>
                    </div>
                    <InputError :message="form.errors.type" />
                </div>

                <div class="grid gap-2">
                    <Label for="quantity">Quantity</Label>
                    <Input id="quantity" v-model="form.quantity" type="number" min="1" placeholder="1" />
                    <InputError :message="form.errors.quantity" />
                </div>

                <div class="grid gap-2">
                    <Label for="notes">Notes</Label>
                    <textarea id="notes" v-model="form.notes"
                        class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="Optional notes..."
                    />
                    <InputError :message="form.errors.notes" />
                </div>

                <div class="flex items-center gap-2">
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Saving...' : 'Save Movement' }}
                    </Button>
                </div>
            </form>
        </div>

        <div v-if="activeTab === 'history'" class="flex flex-col gap-4">
            <div class="flex items-center gap-2">
                <div class="relative w-full max-w-sm">
                    <Search class="text-muted-foreground absolute top-1/2 left-3 size-4 -translate-y-1/2" />
                    <Input v-model="search" placeholder="Search by product name..." class="pl-9" />
                </div>
            </div>

            <div class="rounded-lg border">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-muted/50">
                            <th class="px-4 py-3 text-left text-sm font-medium">Date</th>
                            <th class="px-4 py-3 text-left text-sm font-medium">Product</th>
                            <th class="px-4 py-3 text-left text-sm font-medium">Type</th>
                            <th class="px-4 py-3 text-right text-sm font-medium">Quantity</th>
                            <th class="px-4 py-3 text-left text-sm font-medium">User</th>
                            <th class="px-4 py-3 text-left text-sm font-medium">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="movement in movements.data" :key="movement.id"
                            class="border-b last:border-b-0 hover:bg-muted/50">
                            <td class="px-4 py-3 text-sm text-muted-foreground whitespace-nowrap">
                                {{ new Date(movement.created_at).toLocaleDateString() }}
                            </td>
                            <td class="px-4 py-3 text-sm font-medium">
                                {{ movement.product?.name ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <Badge :variant="badgeVariant(movement.type)">
                                    {{ typeLabel(movement.type) }}
                                </Badge>
                            </td>
                            <td class="px-4 py-3 text-right text-sm tabular-nums">
                                {{ movement.quantity }}
                            </td>
                            <td class="px-4 py-3 text-sm text-muted-foreground">
                                {{ movement.user?.name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-muted-foreground max-w-xs truncate">
                                {{ movement.notes ?? '-' }}
                            </td>
                        </tr>
                        <tr v-if="movements.data.length === 0">
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-muted-foreground">
                                No movements found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination
                :current_page="movements.current_page"
                :last_page="movements.last_page"
                :per_page="movements.per_page"
                :total="movements.total"
            />
        </div>
    </div>
</template>
