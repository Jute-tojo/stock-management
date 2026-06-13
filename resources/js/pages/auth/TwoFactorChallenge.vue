<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from '@lucide/vue';
import AuthCardLayout from '@/layouts/auth/AuthCardLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

defineOptions({ layout: AuthCardLayout });

const form = useForm({
    code: '',
    recovery_code: '',
});

const recovery = ref(false);

const toggleRecovery = () => {
    recovery.value = !recovery.value;
    form.errors.code = '';
    form.errors.recovery_code = '';
};

const submit = () => {
    form.post('/two-factor-challenge', {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Two Factor Challenge" />

    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div v-if="!recovery" class="grid gap-2">
            <Label for="code">Authentication Code</Label>
            <Input
                id="code"
                v-model="form.code"
                type="text"
                inputmode="numeric"
                autocomplete="one-time-code"
                placeholder="Enter your 2FA code"
                :disabled="form.processing"
            />
            <p v-if="form.errors.code" class="text-sm text-destructive">{{ form.errors.code }}</p>
        </div>

        <div v-else class="grid gap-2">
            <Label for="recovery_code">Recovery Code</Label>
            <Input
                id="recovery_code"
                v-model="form.recovery_code"
                type="text"
                autocomplete="off"
                placeholder="Enter a recovery code"
                :disabled="form.processing"
            />
            <p v-if="form.errors.recovery_code" class="text-sm text-destructive">{{ form.errors.recovery_code }}</p>
        </div>

        <Button type="submit" class="w-full" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
            {{ recovery ? 'Log in with Recovery Code' : 'Log in' }}
        </Button>

        <div class="text-center text-sm">
            <button type="button" class="underline underline-offset-4 hover:text-primary" @click="toggleRecovery">
                {{ recovery ? 'Use an authentication code' : 'Use a recovery code' }}
            </button>
        </div>
    </form>
</template>
