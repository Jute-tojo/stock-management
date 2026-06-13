<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from '@lucide/vue';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}>();

const goTo = (page: number) => {
    const params = new URLSearchParams(window.location.search);
    params.set('page', String(page));
    router.get('?' + params.toString(), {}, { preserveState: true, replace: true });
};

const visiblePages = (): number[] => {
    const delta = 2;
    const pages: number[] = [];

    for (let i = Math.max(1, props.current_page - delta); i <= Math.min(props.last_page, props.current_page + delta); i++) {
        pages.push(i);
    }

    return pages;
};
</script>

<template>
    <div v-if="props.last_page > 1" class="flex items-center justify-between gap-4">
        <p class="text-sm text-muted-foreground">
            Page {{ props.current_page }} of {{ props.last_page }} ({{ props.total }} results)
        </p>

        <div class="flex items-center gap-1">
            <Button variant="outline" size="sm" :disabled="props.current_page <= 1" @click="goTo(props.current_page - 1)">
                <ChevronLeft class="size-4" />
            </Button>

            <template v-for="page in visiblePages()" :key="page">
                <Button
                    variant="outline"
                    size="sm"
                    :class="page === props.current_page ? 'bg-primary text-primary-foreground hover:bg-primary/90' : ''"
                    @click="goTo(page)"
                >
                    {{ page }}
                </Button>
            </template>

            <Button variant="outline" size="sm" :disabled="props.current_page >= props.last_page" @click="goTo(props.current_page + 1)">
                <ChevronRight class="size-4" />
            </Button>
        </div>
    </div>
</template>
