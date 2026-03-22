<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): UserCollection
    {
        return new UserCollection(User::all());
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    public function store(UserCreateRequest $request): JsonResponse
    {

        $request->user()->create($request->validated());
        return new JsonResponse($request, 201);
    }

    public function update(User $user, UserCreateRequest $request): JsonResponse
    {
        //TODO:POR ISTO DPS PARA TANTO O ADMIN COM O PROPRIO USER PODEREM ALTERAR
        $user->update($request->validated());
        return response()->json($user);

    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json(null, 204);
    }


}
