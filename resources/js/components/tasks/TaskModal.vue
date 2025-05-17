<script setup lang="ts">
import { Dialog, DialogContent, DialogFooter, DialogHeader } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { reactive } from 'vue';
import { Textarea } from '@/components/ui/textarea';

interface Props {
    task?: object;
}

const props = defineProps<Props>();
const emit = defineEmits([
    'close',
    'save'
]);

const form = reactive({
    title: props.task ? props.task.title : '',
    description: props.task ? props.task.description : '',
    due_date: props.task ? props.task.due_date : '',
});

const submitForm = () => {
    emit(
        'save',
        form,
        () => {
            emit('close');
        }
    );
};
</script>

<template>
<Dialog :open="true" @update:open="$emit('close')">
    <DialogContent>
        <DialogHeader>
            {{ props.task ? 'Edit Task' : 'New Task' }}
        </DialogHeader>
        <form @submit.prevent="submitForm">
            <div class="grid gap-3">
                <div class="grid gap-2">
                    <Label for="title">
                        Title:
                    </Label>
                    <Input
                        id="title"
                        type="text"
                        v-model="form.title"
                        required
                        autofocus
                        tabindex="1"
                        placeholder="Title"
                    />
                </div>
                <div class="grid gap-2">
                    <Label for="description">
                        Description:
                    </Label>
                    <Textarea
                        id="description"
                        v-model="form.description"
                        tabindex="2"
                        placeholder="Description..."
                        class="resize-none overflow-y-auto"
                        rows="5"
                    />
                </div>
                <div class="grid gap-2">
                    <Label for="due_date">
                        Due Date:
                    </Label>
                    <Input
                        id="due_date"
                        type="date"
                        v-model="form.due_date"
                        tabindex="3"
                    />
                </div>
            </div>
        </form>
        <DialogFooter>
            <Button
                type="button"
                variant="secondary"
                class="cursor-pointer"
                @click="submitForm"
            >
                Save
            </Button>
            <Button
                type="button"
                variant="default"
                class="cursor-pointer"
                @click="$emit('close')"
            >
                Cancel
            </Button>
        </DialogFooter>
    </DialogContent>
</Dialog>
</template>

<style scoped>

</style>
