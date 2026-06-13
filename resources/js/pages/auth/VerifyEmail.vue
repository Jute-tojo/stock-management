<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from '@lucide/vue';
import AuthCardLayout from '@/layouts/auth/AuthCardLayout.vue';
import { Button } from '@/components/ui/button';
import { logout } from '@/routes';

defineOptions({ layout: AuthCardLayout });

defineProps<{
    status: string | null;
}>();

const form = useForm({});

const submit = () => {
    form.post('/email/verification-notification', {
        preserveScroll: true,
    });
};

const handleLogout = () => {
    form.post(logout().url);
};
</script>

<template>
    <Head title="Email Verification" />

    <div v-if="status === 'verification-link-sent'" class="mb-4 text-sm font-medium text-green-600">
        A new verification link has been sent to the email address you provided during registration.
    </div>

    <div class="mb-4 text-sm text-muted-foreground">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </div>

    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <Button type="submit" class="w-full" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
            Resend Verification Email
        </Button>

        <div class="text-center text-sm">
            <button type="button" class="underline underline-offset-4 hover:text-primary" @click="handleLogout">
                Log out
            </button>
        </div>
    </form>
</template>
