<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\ChangeTaskStatusRequest;
use App\Http\Requests\Tasks\StoreTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use function Laravel\Prompts\search;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = auth()->user()->tasks()->with('subtasks');
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $tasks = $query->latest()->get();

        return Inertia::render('tasks/Index', [
            'tasks' => $tasks,
            'filters' => $request->only(['status']),
            'statuses' => [
                [
                    'text' => 'All',
                    'value' => 'all',
                ],
                [
                    'text' => 'Todo',
                    'value' => 'todo',
                ],
                [
                    'text' => 'In Progress',
                    'value' => 'in_progress',
                ],
                [
                    'text' => 'Done',
                    'value' => 'done',
                ],
            ],
        ])
            ->with('message', session('success', ''));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        auth()->user()->tasks()->create($request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);

        $task->update($request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    /**
     * Change the specified task's status.
     */
    public function change_status(ChangeTaskStatusRequest $request, Task $task)
    {
        Gate::authorize('update', $task);

        $validated = $request->validated();

        $task->update([
            'status' => $validated['new_status']
        ]);

        return redirect()
            ->route('tasks.index')
            ->with('success', "Task's status changed successfully.");
    }

}
