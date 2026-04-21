<?php

namespace App\Http\Controllers\Api;

use App\DTOs\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection($this->userService->getAll());
    }

    public function store(StoreUserRequest $request): UserResource
    {
        $user = $this->userService->create(UserData::fromRequest($request->validated()));

        return new UserResource($user);
    }

    public function show(int $id): UserResource
    {
        return new UserResource($this->userService->findById($id));
    }

    public function update(UpdateUserRequest $request, int $id): UserResource
    {
        $user = $this->userService->update($id, UserData::fromRequest($request->validated()));

        return new UserResource($user);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->userService->delete($id);

        return response()->json(null, 204);
    }
}
