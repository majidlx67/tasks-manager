<script setup lang="ts">
import { ref } from 'vue';
import {
    Card,
    CardContent, CardDescription,
    CardFooter,
    CardHeader,
    CardTitle
} from '@/components/ui/card';
import { CalendarDays, Check, Hourglass, Settings, Trash } from 'lucide-vue-next';
import { Checkbox } from '@/components/ui/checkbox';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

interface Props {
    task: object;
}

const props = defineProps<Props>();
const emit = defineEmits([
    'edit-task',
    'change-task-status',
    'delete-task',
    'add-subtask',
    'change-subtask-status',
    'delete-subtask'
]);
const newSubTask = ref<string>('');

const changeTaskStatus = (task_id, status) => {
    emit(
        'change-task-status',
        task_id,
        status
    );
};

const deleteTask = (task_id) => {
    emit(
        'delete-task',
        task_id
    );
};

const submitSubTask = (task_id) => {
    if(newSubTask.value) {
        emit(
            'add-subtask',
            task_id,
            newSubTask.value,
            () => {
                newSubTask.value = '';
            }
        );
    }
};

const deleteSubtask = (task_id, subtask_id) => {
    emit(
        'delete-subtask',
        task_id,
        subtask_id
    );
};

const changeSubtaskStatus = (task_id, subtask_id, value) => {
    const status = value ? 'completed' : 'pending';
    emit(
        'change-subtask-status',
        task_id,
        subtask_id,
        status
    );
};

</script>

<template>
    <Card>
        <CardHeader class="border-b">
            <CardTitle>
                {{ task.title }}
            </CardTitle>
            <CardDescription
                class="mt-2"
            >
                <div
                    class="flex gap-1"
                    :class="{
                            'text-todo': task.status === 'todo',
                            'text-in-progress' : task.status === 'in_progress',
                            'text-done' : task.status === 'done',
                            }"
                >
                    <Hourglass
                        v-if="task.status === 'todo'"
                        class="size-4"
                    />
                    <Settings
                        v-if="task.status === 'in_progress'"
                        class="size-4"
                    />
                    <Check
                        v-if="task.status === 'done'"
                        class="size-4"
                    />
                    {{ task.status_text }}
                </div>
                <div
                    v-if="task.due_date"
                    class="flex gap-1 mt-1"
                    :class="{'text-overdue': task.is_overdue}"
                >
                    <CalendarDays class="size-4" />
                    {{ task.due_date }}
                </div>
            </CardDescription>
        </CardHeader>
        <CardContent>
            <p>
                {{ task.description }}
            </p>
            <div class="mt-3">
                <h3 class="font-bold mb-2">Subtasks:</h3>
                <ul
                    v-if="task.subtasks && task.subtasks.length > 0"
                    class="flex flex-col gap-2 mb-2"
                >
                    <li
                        v-for="subtask in task.subtasks"
                        :key="subtask.id"
                        class="flex items-center gap-1"
                    >
                        <Checkbox
                            :default-value="subtask.status === 'completed'"
                            @update:model-value="value => changeSubtaskStatus(task.id, subtask.id, value)"
                        />
                        <span class="grow">{{ subtask.title }}</span>
                        <Button
                            type="button"
                            variant="link"
                            @click="deleteSubtask(task.id, subtask.id)"
                            class="cursor-pointer text-red-700"
                        >
                            <Trash class="size-6" />
                        </Button>
                    </li>
                </ul>
                <form @submit.prevent="submitSubTask(task.id)">
                    <Input v-model="newSubTask" />
                </form>
            </div>
        </CardContent>
        <CardFooter class="border-t flex gap-2">
            <Button
                type="button"
                variant="secondary"
                class="cursor-pointer"
                @click="$emit('edit-task', task)"
            >
                Edit
            </Button>
            <Button
                type="button"
                variant="destructive"
                class="cursor-pointer"
                @click="deleteTask(task.id)"
            >
                Delete
            </Button>
            <Button
                type="button"
                class="bg-todo hover:bg-todo/80 text-primary cursor-pointer"
                v-if="task.status === 'in_progress'"
                @click="changeTaskStatus(task.id, 'todo')"
            >
                Todo
            </Button>
            <Button
                type="button"
                class="bg-in-progress hover:bg-in-progress/80 text-primary cursor-pointer"
                v-if="task.status === 'todo' || task.status === 'done'"
                @click="changeTaskStatus(task.id, 'in_progress')"
            >
                In Progress
            </Button>
            <Button
                type="button"
                class="bg-done hover:bg-done/80 text-primary cursor-pointer"
                v-if="task.status === 'in_progress'"
                @click="changeTaskStatus(task.id, 'done')"
            >
                Done
            </Button>
        </CardFooter>
    </Card>
</template>

<style scoped>

</style>
