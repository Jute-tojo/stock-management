<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from '@lucide/vue';
import AuthCardLayout from '@/layouts/auth/AuthCardLayout.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { login, register } from '@/routes';
import { store } from '@/routes/login';

defineOptions({ layout: AuthCardLayout });

defineProps<{
    canResetPassword: boolean;
    status: string | null;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(store().url, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Log in" />

    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
        {{ status }}
    </div>

    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div class="grid gap-6">
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

            <div class="grid gap-2">
                <div class="flex items-center">
                    <Label for="password">Password</Label>
                    <Link
                        v-if="canResetPassword"
                        :href="login()"
                        class="ml-auto text-sm underline-offset-4 hover:underline"
                    >
                        Forgot password?
                    </Link>
                </div>
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

            <div class="flex items-center gap-2">
                <Checkbox id="remember" v-model:checked="form.remember" />
                <Label for="remember">Remember me</Label>
            </div>

            <Button type="submit" class="w-full" :disabled="form.processing">
                <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
                Log in
            </Button>
        </div>

        <div class="text-center text-sm">
            Don't have an account?
            <Link :href="register()" class="underline underline-offset-4 hover:text-primary">
                Sign up
            </Link>
        </div>
    </form>
</template>
