<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { home } from '@/routes';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const techs = [
    { name: 'Laravel', desc: 'PHP Framework' },
    { name: 'Inertia.js', desc: 'Modern Monolithic SPA' },
    { name: 'Vue.js 3', desc: 'Frontend Framework' },
    { name: 'TypeScript', desc: 'Type Safety' },
    { name: 'Tailwind CSS v4', desc: 'Utility-first CSS' },
    { name: 'shadcn-vue', desc: 'UI Components' },
    { name: 'Reka UI', desc: 'Headless UI Primitives' },
    { name: 'Laravel Fortify', desc: 'Authentication Backend' },
    { name: 'Passkeys', desc: 'Passwordless Auth (WebAuthn)' },
    { name: 'Lucide', desc: 'Icons' },
    { name: 'vue-sonner', desc: 'Toast Notifications' },
    { name: '@vueuse/core', desc: 'Vue Composables' },
];
</script>

<template>
    <Head title="Welcome" />

    <div class="flex min-h-svh flex-col items-center justify-center gap-8 bg-background p-6 md:p-10">
        <div class="w-full max-w-lg">
            <div class="flex flex-col items-center gap-6 text-center">
                <Link :href="home()">
                    <AppLogoIcon class="size-12 fill-current text-[var(--foreground)] dark:text-white" />
                </Link>

                <div class="space-y-2">
                    <h1 class="text-2xl font-semibold tracking-tight">
                        Ramaroson Tojo Fanomezantsoa
                    </h1>
                    <p class="text-sm text-muted-foreground">
                        Stock Management System
                        by tojoramaroson5@gmail.com
                    </p>
                </div>

                <div class="w-full space-y-3">
                    <h2 class="text-sm font-medium text-muted-foreground uppercase tracking-wider">
                        Built with
                    </h2>
                    <div class="grid grid-cols-2 gap-3">
                        <div
                            v-for="tech in techs"
                            :key="tech.name"
                            class="rounded-lg border bg-card p-3 text-left"
                        >
                            <p class="text-sm font-medium">{{ tech.name }}</p>
                            <p class="text-xs text-muted-foreground">{{ tech.desc }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <Link
                        href="/login"
                        class="text-sm text-muted-foreground underline underline-offset-4 hover:text-foreground"
                    >
                        Login
                    </Link>
                    <Link
                        href="/register"
                        class="text-sm text-muted-foreground underline underline-offset-4 hover:text-foreground"
                    >
                        Register
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
