<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from '@lucide/vue';
import AuthCardLayout from '@/layouts/auth/AuthCardLayout.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineOptions({ layout: AuthCardLayout });

defineProps<{
    passwordRules: string;
}>();

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(store().url, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Register" />

    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input
                    id="name"
                    v-model="form.name"
                    type="text"
                    autocomplete="name"
                    placeholder="Full name"
                    :disabled="form.processing"
                />
                <p v-if="form.errors.name" class="text-sm text-destructive">{{ form.errors.name }}</p>
            </div>

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
                <Label for="password">Password</Label>
                <Input
                    id="password"
                    v-model="form.password"
                    type="password"
                    autocomplete="new-password"
                    placeholder="Password"
                    :disabled="form.processing"
                />
                <p v-if="form.errors.password" class="text-sm text-destructive">{{ form.errors.password }}</p>
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">Confirm Password</Label>
                <Input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    placeholder="Confirm password"
                    :disabled="form.processing"
                />
                <p v-if="form.errors.password_confirmation" class="text-sm text-destructive">{{ form.errors.password_confirmation }}</p>
            </div>

            <div class="flex items-center gap-2">
                <Checkbox id="terms" v-model:checked="form.terms" />
                <Label for="terms">
                    I agree to the
                    <a href="#" class="underline underline-offset-4 hover:text-primary">Terms and Conditions</a>
                </Label>
            </div>

            <Button type="submit" class="w-full" :disabled="form.processing">
                <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
                Create account
            </Button>
        </div>

        <div class="text-center text-sm">
            Already have an account?
            <Link :href="login()" class="underline underline-offset-4 hover:text-primary">
                Log in
            </Link>
        </div>
    </form>
</template>
