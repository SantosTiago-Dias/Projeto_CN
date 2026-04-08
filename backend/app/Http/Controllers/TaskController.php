<?php

namespace App\Http\Controllers;


use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskEditRequest;
use App\Http\Resources\TaskCollection;
use App\Jobs\NotificationJob;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    //
    public function index(): TaskCollection
    {
        $tasks= Task::query()->orderBy('due_date','desc')->paginate();
        return new TaskCollection($tasks);
    }


    public function show(Task $task): JsonResponse
    {
        return response()->json($task);
    }

    public function getTaskUser(): TaskCollection
    {
        $user = auth()->user();
        if ($user->isAdmin())
        {
            abort(403);
        }

        $tasks = Task::where('worker_id', $user->id)->whereDate('due_date', today())->orderBy('due_date', 'desc')->get();

        return new TaskCollection($tasks);

    }

    public function getTaskToday(): TaskCollection
    {
        $user = auth()->user();
        if ($user->isAdmin())
        {
            abort(403);
        }

        $tasks = Task::where('worker_id', $user->id)->whereDate('due_date', today())->orderBy('due_date', 'desc')->get();

        return new TaskCollection($tasks);

    }

    public function store(TaskCreateRequest $request): JsonResponse
    {
        $request->validated();
        $task= Task::create([
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => $request->status,
            'priority'    => $request->priority,
            'due_date'    => $request->due_date,
            'outside' => $request->outside,
            'admin_id'    => $request->user()->id,
            'worker_id'     => $request->worker_id
        ]);

        NotificationJob::dispatch($request->user()->id,$request->worker_id,$task);


        return new JsonResponse($task);
    }

    public function update(TaskEditRequest $request, Task $task): JsonResponse
    {
        $task->update($request->validated());
        return response()->json($task);
    }

    public function changeStatus(Task $task,Request $request): JsonResponse
    {

        $validated = $request->validate([
            'status' => 'required|in:IN_PROGRESS,COMPLETED,CANCELLED',
            'reason_cancelled' => 'required_if:status,CANCELLED|nullable|string|max:500',
            'reason_complete' => 'required_if:status,COMPLETED|nullable|file|mimes:jpeg,jpg,png|max:2048'
        ]);

        if ($request->status === 'COMPLETED') {
            $path = $request->file('file')->store('', 's3');

            $request['reason_complete'] = Storage::disk('s3')->url($path);
        }

        $task->update($validated);
        NotificationJob::dispatch($task->worker_id,$task->admin_id,$task);
        return response()->json($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();
        return response()->json(null,204);
    }
}
