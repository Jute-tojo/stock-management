<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

defineOptions({ layout: AppLayout });
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-col gap-6 p-4">
        <Heading title="Dashboard" description="Welcome to Stock Management" />

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <Card>
                <CardHeader>
                    <CardTitle>Products</CardTitle>
                    <CardDescription>Manage your product catalog</CardDescription>
                </CardHeader>
                <CardContent>
                    <a href="/products" class="text-sm text-primary underline underline-offset-4">View products</a>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Clients</CardTitle>
                    <CardDescription>Manage your clients</CardDescription>
                </CardHeader>
                <CardContent>
                    <a href="/clients" class="text-sm text-primary underline underline-offset-4">View clients</a>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Stock</CardTitle>
                    <CardDescription>Track inventory movements</CardDescription>
                </CardHeader>
                <CardContent>
                    <span class="text-sm text-muted-foreground">Coming soon</span>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
