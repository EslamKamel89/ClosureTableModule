<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbsLinks: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: '/categories',
    },
];
interface CategoryNode {
    id: number;
    name: string;
    slug: string;
    is_leaf: boolean;
    product_count: number;
    children: CategoryNode[];
}
interface BreadcrumbType {
    id: number;
    name: string;
    slug: string;
    is_leaf?: boolean;
}
defineProps<{
    tree: CategoryNode;
}>();
const breadcrumbs = ref<BreadcrumbType[]>([]);
</script>

<template>
    <Head title="Categories" />

    <AppLayout :breadcrumbs="breadcrumbsLinks">
        <div class="flex flex-col flex-1 h-full gap-4 p-4 overflow-x-auto rounded-xl">
            <pre>
                {{ tree }}
            </pre>
        </div>
    </AppLayout>
</template>
