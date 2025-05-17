<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subtasks\ChangeSubtaskStatusRequest;
use App\Http\Requests\Subtasks\StoreSubtaskRequest;
use App\Http\Requests\Subtasks\UpdateSubtaskRequest;
use App\Models\SubTask;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SubTaskController extends Controller
{
    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreSubtaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);

        $task->subtasks()->create($request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Subtask added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubtaskRequest $request, Task $task, SubTask $subtask)
    {
        Gate::authorize('update', $subtask->task);

        $subtask->update($request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Subtask updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, SubTask $subtask)
    {
        Gate::authorize('update', $subtask->task);

        $subtask->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Subtask deleted successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function change_status(ChangeSubtaskStatusRequest $request, Task $task, SubTask $subtask)
    {
        Gate::authorize('update', $subtask->task);

        $validated = $request->validated();

        $subtask->update(
            [
                'status' => $validated['new_status'],
            ]
        );

        /*return to_route('tasks.index')
            ->with("Subtask's status changed successfully.");*/

        return redirect()
            ->route('tasks.index')
            ->with('success', "Subtask's status changed successfully.");
    }
}
