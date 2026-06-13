<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import { Plus, Trash2 } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import {
    Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle,
} from '@/components/ui/dialog';
import { store as storeRoute, destroy as destroyRoute } from '@/routes/categories';
import type { Category } from '@/types';

defineProps<{
    categories: Category[];
}>();

const emit = defineEmits<{
    close: [];
}>();

const form = useForm<{ name: string }>({ name: '' });

const submit = () => {
    form.post(storeRoute.url(), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => form.reset(),
    });
};

const remove = (category: Category) => {
    router.delete(destroyRoute.url(category.id), {
        preserveScroll: true,
        preserveState: true,
    });
};
</script>

<template>
    <Dialog :open="true" @update:open="emit('close')">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Manage Categories</DialogTitle>
                <DialogDescription>
                    Add or remove product categories.
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-4">
                <div class="flex items-end gap-2">
                    <div class="flex-1 grid gap-2">
                        <Label for="category-name">New Category</Label>
                        <Input id="category-name" v-model="form.name" placeholder="Category name" />
                        <InputError :message="form.errors.name" />
                    </div>
                    <Button type="submit" :disabled="form.processing">
                        <Plus class="size-4" />
                        Add
                    </Button>
                </div>
            </form>

            <div v-if="categories.length > 0" class="space-y-1">
                <p class="text-sm font-medium text-muted-foreground">Existing categories</p>
                <div v-for="cat in categories" :key="cat.id"
                    class="flex items-center justify-between rounded-md border px-3 py-2"
                >
                    <div class="flex items-center gap-2">
                        <span class="text-sm">{{ cat.name }}</span>
                        <Badge variant="secondary" class="text-xs">{{ cat.products_count ?? 0 }} products</Badge>
                    </div>
                    <Button variant="ghost" size="icon-sm" @click="remove(cat)">
                        <Trash2 class="size-4 text-destructive" />
                    </Button>
                </div>
            </div>

            <p v-else class="text-sm text-muted-foreground">No categories yet.</p>
        </DialogContent>
    </Dialog>
</template>
