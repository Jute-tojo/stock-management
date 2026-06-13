<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from '@lucide/vue';
import AuthCardLayout from '@/layouts/auth/AuthCardLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

defineOptions({ layout: AuthCardLayout });

const form = useForm({
    password: '',
});

const submit = () => {
    form.post('/user/confirm-password', {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Confirm Password" />

    <div class="mb-4 text-sm text-muted-foreground">
        This is a secure area of the application. Please confirm your password before continuing.
    </div>

    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div class="grid gap-2">
            <Label for="password">Password</Label>
            <Input
                id="password"
                v-model="form.password"
                type="password"
                autocomplete="current-password"
                placeholder="Password"
                :disabled="form.processing"
            />
            <p v-if="form.errors.password" class="text-sm text-destructive">{{ form.errors.password }}</p>
        </div>

        <Button type="submit" class="w-full" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
            Confirm
        </Button>
    </form>
</template>
