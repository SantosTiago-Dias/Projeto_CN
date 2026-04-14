<?php

namespace App\Http\Controllers;


use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskEditRequest;
use App\Http\Resources\TaskCollection;
use App\Jobs\NotificationJob;
use App\Models\Task;
use App\Models\User;
use Aws\S3\S3Client;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        NotificationJob::dispatch($request->user()->id,$request->worker_id,$task,'updated');


        return new JsonResponse($task);
    }

    public function update(TaskEditRequest $request, Task $task): JsonResponse
    {
        $task->update($request->validated());
        return response()->json($task);
    }

    public function changeStatus(Task $task, Request $request): JsonResponse
    {
        if ($task->worker_id !== auth()->id() && auth()->user()->isAdmin()) {
            abort(403);
        }
        $validated = $request->validate([
            'status' => 'required|in:IN_PROGRESS,COMPLETED,CANCELLED',
            'reason_cancelled' => 'required_if:status,CANCELLED|nullable|string|max:500',
            'prove_complete' => 'required_if:status,COMPLETED|nullable|string'
        ]);

        $task->update($validated);

        NotificationJob::dispatch($task->worker_id, $task->admin_id, $task);

        return response()->json($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();
        return response()->json(null,204);
    }

    public function uploadImage(): JsonResponse
    {
        $filename = time() . '-' . Str::random(10) . '.jpg';
        $key = 'proof_images/' . $filename;
        $BUCKET = config('filesystems.disks.s3.bucket');

        $client = new S3Client([
            'version'                 => 'latest',
            'region'                  => config('filesystems.disks.s3.region'),
            'credentials'             => [
                'key'    => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
            'endpoint'                => config('filesystems.disks.s3.url'),
            'use_path_style_endpoint' => config('filesystems.disks.s3.use_path_style_endpoint'),
        ]);

        $command = $client->getCommand('PutObject', [
            'Bucket'      => $BUCKET,
            'Key'         => $key,
            'ContentType' => 'image/jpeg',
        ]);

        $presignedUrl = $client->createPresignedRequest($command, '+10 minutes')->getUri();

        return response()->json([
            'upload_url' => (string) $presignedUrl,
            'filename'   => $filename,
        ]);
    }
}
