<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from '@lucide/vue';
import { Button } from '@/components/ui/button';

type PaginationMeta = {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
};

const props = defineProps<{
    meta: PaginationMeta;
}>();

const goTo = (page: number) => {
    const params = new URLSearchParams(window.location.search);
    params.set('page', String(page));
    router.get('?' + params.toString(), {}, { preserveState: true, replace: true });
};

const visiblePages = (): number[] => {
    const { current_page, last_page } = props.meta;
    const delta = 2;
    const pages: number[] = [];

    for (let i = Math.max(1, current_page - delta); i <= Math.min(last_page, current_page + delta); i++) {
        pages.push(i);
    }

    return pages;
};
</script>

<template>
    <div v-if="meta.last_page > 1" class="flex items-center justify-between gap-4">
        <p class="text-sm text-muted-foreground">
            Page {{ meta.current_page }} of {{ meta.last_page }} ({{ meta.total }} results)
        </p>

        <div class="flex items-center gap-1">
            <Button variant="outline" size="sm" :disabled="meta.current_page <= 1" @click="goTo(meta.current_page - 1)">
                <ChevronLeft class="size-4" />
            </Button>

            <template v-for="page in visiblePages()" :key="page">
                <Button
                    variant="outline"
                    size="sm"
                    :class="page === meta.current_page ? 'bg-primary text-primary-foreground hover:bg-primary/90' : ''"
                    @click="goTo(page)"
                >
                    {{ page }}
                </Button>
            </template>

            <Button variant="outline" size="sm" :disabled="meta.current_page >= meta.last_page" @click="goTo(meta.current_page + 1)">
                <ChevronRight class="size-4" />
            </Button>
        </div>
    </div>
</template>
