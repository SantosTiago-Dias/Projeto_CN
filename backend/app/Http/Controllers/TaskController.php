<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
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

    public function getTaskUser(Request $request)
    {
        return $request->worker_id;
        /*
        $user = User::all()->find($request->worker_id);
        if ($user == null) {
            abort(401,'Este utilizador não existe');
        }
        $tasks= Task::query()->where('worker_id', $user->id)->paginate();
        return new TaskCollection($tasks);
        */
    }

    public function show(Task $task): JsonResponse
    {
        return response()->json($task);
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $request->task()->create($request->validated());
        return new JsonResponse($request, 201);
    }

    public function update(TaskRequest $request, Task $task): JsonResponse
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
        $request->validate([
            'status'=>'required','IN_PROGRESS', 'COMPLETED','CANCELLED'
        ]);
        $task->update($request->validated());
        return response()->json($task);
    }

}
