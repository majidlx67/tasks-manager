<script setup lang="ts">
import { onUnmounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import TaskItem from '@/components/tasks/TaskItem.vue';
import { toast } from 'vue-sonner';
import TaskModal from '@/components/tasks/TaskModal.vue';

interface Props {
    tasks: array;
    statuses: array;
    filters?: object;
}

const props = defineProps<Props>();

const filter = ref(props.filters && props.filters.status ? props.filters.status : props.statuses[0].value);
const selectedTask = ref(undefined);
const showTaskModal = ref<boolean>(false);

onUnmounted(
    () => {
        if(props.message) {
            toast('Event has been created', {
                description: 'Sunday, December 03, 2023 at 9:00 AM',
                action: {
                    label: 'Undo',
                    onClick: () => console.log('Undo'),
                },
            })
        }
    }
);

const filterTasks = () => {
    router.get('/tasks', { status: filter.value });
};

const openModal = (task: any) => {
    selectedTask.value = task;
    showTaskModal.value = true;
};

const closeModal = () => {
    selectedTask.value = undefined;
    showTaskModal.value = false;
};

// Task handlers
const addTask = (data, onSuccess = undefined) => {
    const queryString = filter.value ? `?status=${filter.value}` : '';
    router.post(
        route('tasks.store') + queryString,
        data,
        {
            onSuccess: onSuccess,
        }
    );
};
const editTask = (task_id, data, onSuccess = undefined) => {
    const queryString = filter.value ? `?status=${filter.value}` : '';
    router.put(
        route('tasks.update', {task: task_id}) + queryString,
        data,
        {
            onSuccess: onSuccess,
        }
    );
};
const handleSave = (data, onSuccess = undefined) => {
    if (selectedTask.value) {
        editTask(selectedTask.value.id, data, onSuccess);
    } else {
        addTask(data, onSuccess);
    }
};
const deleteTask = (task_id, onSuccess = undefined) => {
    const queryString = filter.value ? `?status=${filter.value}` : '';
    router.delete(
        route('tasks.destroy', {task: task_id}) + queryString,
        {
            onSuccess: onSuccess,
        }
    );
};
const changeTaskStatus = (task_id, new_status, onSuccess = undefined) => {
    const queryString = filter.value ? `?status=${filter.value}` : '';
    router.post(
        route('tasks.status', {task: task_id}) + queryString,
        {
            new_status: new_status,
        },
        {
            onSuccess: onSuccess,
        }
    );
};

// Subtask handlers
const addSubTask = (task_id, title, onSuccess = undefined) => {
    const queryString = filter.value ? `?status=${filter.value}` : '';
    router.post(
        route('subtasks.store', {task: task_id}) + queryString,
        {
            title: title,
        },
        {
            onSuccess: onSuccess,
        }
    );
};

const changeSubTaskStatus = (task_id, subtask_id, new_status, onSuccess = undefined) => {
    const queryString = filter.value ? `?status=${filter.value}` : '';
    router.post(
        route('subtasks.status', {task: task_id, subtask: subtask_id}) + queryString,
        {
            new_status: new_status,
        },
        {
            onSuccess: onSuccess,
        }
    );
};

const deleteSubTask = (task_id, subtask_id, onSuccess = undefined) => {
    const queryString = filter.value ? `?status=${filter.value}` : '';
    ;    router.delete(
        route('subtasks.destroy', {task: task_id, subtask: subtask_id}) + queryString,
        {
            onSuccess: onSuccess,
        }
    );
};
</script>

<template>
    <div class="p-4">
        <div class="flex justify-between mb-4">
            <Select
                :default-value="filter ? filter : statuses[0].value"
                v-model="filter"
                @update:modelValue="filterTasks"
            >
                <SelectTrigger class="w-32">
                    <SelectValue placeholder="Select an status" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="(status, index) in statuses"
                        :key="index"
                        :value="status.value"
                    >
                        {{ status.text }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <Button
                type="button"
                class="cursor-pointer"
                @click="openModal(undefined)"
                variant="secondary"
            >
                <Plus />
                New Task
            </Button>
        </div>
        <TaskModal
            v-if="showTaskModal"
            :task="selectedTask"
            @close="closeModal"
            @save="handleSave"
        />
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <TaskItem
                v-for="task in props.tasks"
                :key="task.id"
                :task="task"
                @edit-task="openModal"
                @change-task-status="changeTaskStatus"
                @delete-task="deleteTask"
                @add-subtask="addSubTask"
                @change-subtask-status="changeSubTaskStatus"
                @delete-subtask="deleteSubTask"
            />
        </div>
    </div>
</template>

<style scoped>

</style>
