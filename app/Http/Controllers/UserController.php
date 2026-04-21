<?php

namespace App\Http\Controllers;

use App\DTOs\UserData;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    public function index(): View
    {
        $search = request()->string('search')->toString();

        return view('users.index', [
            'users' => $this->userService->getAll($search),
        ]);
    }

    public function create(): View
    {
        return view('users.create', [
            'user' => new User(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = $this->userService->create(
            UserData::fromRequest($request->validated()),
            $request->file('avatar')
        );

        if (auth()->check()) {
            return redirect()
                ->route('users.show', $user)
                ->with('status', 'User created successfully.');
        }

        return redirect()
            ->route('login')
            ->with('status', 'Cadastro realizado com sucesso. Faça login para continuar.');
    }

    public function show(User $user): View
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $updatedUser = $this->userService->update(
            $user->id,
            UserData::fromRequest($request->validated()),
            $request->file('avatar')
        );

        return redirect()
            ->route('users.show', $updatedUser)
            ->with('status', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->userService->delete($user->id);

        return redirect()
            ->route('users.index')
            ->with('status', 'User archived successfully.');
    }
}
