<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { useProductForm } from '@/composables/useProductForm';
import type { Category } from '@/types';

defineProps<{
    categories: Category[];
    units: Array<{ value: string; label: string }>;
}>();

const {
    form,
    isOpen,
    isEdit,
    editingProduct,
    submit,
    openCreateModal,
    openEditModal,
    closeModal,
} = useProductForm();

defineExpose({ openCreateModal, openEditModal });
</script>

<template>
    <Dialog :open="isOpen" @update:open="closeModal">
        <DialogContent class="sm:max-w-lg">
            <DialogHeader>
                <DialogTitle>{{ isEdit ? 'Edit Product' : 'New Product' }}</DialogTitle>
                <DialogDescription>
                    {{ isEdit ? 'Update the product details below.' : 'Fill in the details to create a new product.' }}
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-4">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" v-model="form.name" placeholder="Product name" />
                    <InputError :message="form.errors.name" />
                </div>

                <div v-if="isEdit" class="grid gap-2">
                    <Label for="sku">SKU</Label>
                    <Input id="sku" v-model="form.sku" disabled />
                    <InputError :message="form.errors.sku" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label for="category">Category</Label>
                        <Select v-model="form.category_id">
                            <SelectTrigger id="category">
                                <SelectValue placeholder="Select category" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="cat in categories" :key="cat.id" :value="cat.id">
                                    {{ cat.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.category_id" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="unit">Unit</Label>
                        <Select v-model="form.unit">
                            <SelectTrigger id="unit">
                                <SelectValue placeholder="Select unit" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="unit in units" :key="unit.value" :value="unit.value">
                                    {{ unit.label }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.unit" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label for="price">Price</Label>
                        <Input id="price" v-model="form.price" type="number" step="0.01" min="0" placeholder="0.00" />
                        <InputError :message="form.errors.price" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="quantity">Quantity</Label>
                        <Input id="quantity" v-model="form.quantity" type="number" min="0" placeholder="0" />
                        <InputError :message="form.errors.quantity" />
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label for="description">Description</Label>
                    <Input id="description" v-model="form.description" placeholder="Optional description" />
                    <InputError :message="form.errors.description" />
                </div>

                <div class="grid gap-2">
                    <Label for="image">Image</Label>
                    <Input id="image" type="file" accept="image/jpeg,image/png,image/gif,image/webp" @input="form.image = ($event.target as HTMLInputElement).files?.[0] ?? null" />
                    <div v-if="isEdit && editingProduct?.image_url" class="mt-2">
                        <img :src="editingProduct.image_url" alt="Current image" class="h-20 w-20 rounded-md object-cover" />
                    </div>
                    <InputError :message="form.errors.image" />
                </div>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="closeModal">Cancel</Button>
                    <Button type="submit" :disabled="form.processing">
                        {{ isEdit ? 'Update' : 'Create' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
