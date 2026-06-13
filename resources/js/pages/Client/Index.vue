<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Search, Plus, Pencil, Trash2 } from '@lucide/vue';
import { useDebounceFn } from '@vueuse/core';
import { watch, ref } from 'vue';
import { index as clientIndex } from '@/routes/clients';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useClientForm } from '@/composables/useClientForm';
import type { ClientPaginate } from '@/types';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Clients', href: '/clients' },
        ],
    },
});

const props = defineProps<{
    clients: ClientPaginate;
    filters: string | null;
}>();

const search = ref(props.filters ?? '');

const debouncedSearch = useDebounceFn((value: string) => {
    router.get(clientIndex.url({ query: { search: value || null } }), {}, {
        preserveState: true,
        replace: true,
    });
}, 3000);

watch(search, (val) => {
    debouncedSearch(val);
});

const { form, isEdit, resetForm, startEdit, submit, destroy } = useClientForm();
</script>

<template>
    <Head title="Clients" />

    <div class="flex flex-col gap-6 p-4">
        <div class="flex items-center justify-between">
            <Heading title="Clients" description="Manage your clients" />
            <Button @click="resetForm()">
                <Plus class="size-4" />
                New Client
            </Button>
        </div>

        <div class="rounded-lg border bg-card p-6">
            <h3 class="mb-4 text-lg font-semibold">{{ isEdit ? 'Edit Client' : 'New Client' }}</h3>
            <form @submit.prevent="submit" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input id="name" v-model="form.name" placeholder="Client name" />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input id="email" v-model="form.email" type="email" placeholder="client@example.com" />
                        <InputError :message="form.errors.email" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label for="phone">Phone</Label>
                        <Input id="phone" v-model="form.phone" placeholder="+221 XX XXX XX XX" />
                        <InputError :message="form.errors.phone" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="address">Address</Label>
                        <Input id="address" v-model="form.address" placeholder="Client address" />
                        <InputError :message="form.errors.address" />
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <Button type="submit" :disabled="form.processing">
                        {{ isEdit ? 'Update' : 'Create' }}
                    </Button>
                    <Button v-if="isEdit" type="button" variant="outline" @click="resetForm">
                        Cancel
                    </Button>
                </div>
            </form>
        </div>

        <div class="flex items-center gap-2">
            <div class="relative w-full max-w-sm">
                <Search class="text-muted-foreground absolute top-1/2 left-3 size-4 -translate-y-1/2" />
                <Input v-model="search" placeholder="Search by name, email or phone..." class="pl-9" />
            </div>
        </div>

        <div class="rounded-lg border">
            <table class="w-full">
                <thead>
                    <tr class="border-b bg-muted/50">
                        <th class="px-4 py-3 text-left text-sm font-medium">#</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Name</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Email</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Phone</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Address</th>
                        <th class="px-4 py-3 text-right text-sm font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="client in clients.data" :key="client.id"
                        class="border-b last:border-b-0 hover:bg-muted/50">
                        <td class="px-4 py-3 text-sm text-muted-foreground">{{ client.id }}</td>
                        <td class="px-4 py-3 text-sm font-medium">{{ client.name }}</td>
                        <td class="px-4 py-3 text-sm">{{ client.email }}</td>
                        <td class="px-4 py-3 text-sm">{{ client.phone ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">{{ client.address }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <Button variant="ghost" size="icon-sm" @click="startEdit(client)">
                                    <Pencil class="size-4" />
                                </Button>
                                <Button variant="ghost" size="icon-sm" @click="destroy(client)">
                                    <Trash2 class="size-4 text-destructive" />
                                </Button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="clients.data.length === 0">
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-muted-foreground">
                            No clients found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination
            :current_page="clients.current_page"
            :last_page="clients.last_page"
            :per_page="clients.per_page"
            :total="clients.total"
        />
    </div>
</template>
