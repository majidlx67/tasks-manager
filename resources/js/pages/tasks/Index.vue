<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import TaskList from '@/components/tasks/TaskList.vue';
import Sonner from '@/components/ui/sonner/Sonner.vue';
import { onUpdated } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tasks',
        href: '/tasks',
    },
];

interface Props {
    tasks: object;
    filters: object;
    message: string;
}

const props = defineProps<Props>();

const page = usePage<SharedData>();
const statuses = page.props.statuses;

onUpdated(
    () => {
        if(props.message) {
            toast(props.message);
        }
    }
);
</script>

<template>
    <Head title="Tasks" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <TaskList :tasks="tasks" :statuses="statuses" :filters="filters" />
        <Sonner />
    </AppLayout>
</template>
