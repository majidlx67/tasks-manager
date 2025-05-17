<?php

/*test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});*/

use App\Models\SubTask;
use App\Models\Task;
use App\Models\User;

it('can create a task', function () {
    $randNum = time();
    $user = User::factory()->create();
    $this->actingAs($user)
        ->post(route('tasks.store'), [
            'title' => 'Test Task' . $randNum,
            'description' => 'Description',
            'due_date' => now()->addDay()->toDateString(),
        ])
        ->assertRedirect()
        ->assertSessionHas('success', 'Task created successfully.');

    expect(Task::where('title', 'Test Task' . $randNum)->exists())->toBeTrue();
});

it('can update task status when all subtasks are done', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);
    $subtask = Subtask::factory()->create(['task_id' => $task->id]);

    $this->actingAs($user)
        ->post(
            route('subtasks.status', ['task' => $task->id, 'subtask' => $subtask->id]),
            ['new_status' => 'completed']
        )
        ->assertRedirect();

    expect($task->fresh()->status)->toBe('done');
});

it('can delete a task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->delete(route('tasks.destroy', ['task' => $task->id]))
        ->assertRedirect()
        ->assertSessionHas('success', 'Task deleted successfully.');

    expect(Task::where('id', $task->id)->exists())->toBeFalse();
});
