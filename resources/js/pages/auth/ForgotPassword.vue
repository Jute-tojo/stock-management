<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from '@lucide/vue';
import AuthCardLayout from '@/layouts/auth/AuthCardLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { login } from '@/routes';
import { email } from '@/routes/password';

defineOptions({ layout: AuthCardLayout });

defineProps<{
    status: string | null;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(email().url, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Forgot Password" />

    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
        {{ status }}
    </div>

    <div class="mb-4 text-sm text-muted-foreground">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
    </div>

    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input
                id="email"
                v-model="form.email"
                type="email"
                autocomplete="email"
                placeholder="email@example.com"
                :disabled="form.processing"
            />
            <p v-if="form.errors.email" class="text-sm text-destructive">{{ form.errors.email }}</p>
        </div>

        <Button type="submit" class="w-full" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
            Email Password Reset Link
        </Button>

        <div class="text-center text-sm">
            <Link :href="login()" class="underline underline-offset-4 hover:text-primary">
                Back to login
            </Link>
        </div>
    </form>
</template>
