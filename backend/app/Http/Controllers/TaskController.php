<?php

namespace App\Http\Controllers;


use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskEditRequest;
use App\Http\Resources\TaskCollection;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //
    public function index(): TaskCollection
    {
        $tasks= Task::query()->paginate();
        return new TaskCollection($tasks);
    }

    public function getTaskUser(Request $request):TaskCollection
    {


        $user = User::all()->find($request->worker_id);
        if ($user == null) {
            abort(401,'Este utilizador não existe');
        }

        $tasks= Task::query()->where('worker_id', $user->id)->paginate();
        return new TaskCollection($tasks);

    }

    public function show(Task $task): JsonResponse
    {
        return response()->json($task);
    }

    public function store(TaskCreateRequest $request)
    {
        $request->validated();
        $task= Task::create([
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => 'PENDING',
            'priority'    => $request->priority,
            'due_date'    => $request->due_date,
            'admin_id'    => $request->user()->id,
            'worker_id'     => $request->user_id
        ]);

        return new JsonResponse($task);
    }

    public function update(TaskEditRequest $request, Task $task): JsonResponse
    {
        $task->update($request->validated());
        return response()->json($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();
        return response()->json(null, 204);
    }

    public function changeStatus(Task $task,Request $request): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:IN_PROGRESS,COMPLETED,CANCELLED'
        ]);

        $task->update($validated);
        return response()->json($task);
    }

}
