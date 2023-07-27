<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserStatusRequest;
use App\Models\File;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::with('role')
            ->where('email', '<>', 'crystaldesarrollo@gmail.com')
            ->orderBy('order')
            ->get();

        return response()->json($users, Response::HTTP_OK);
    }

    public function show(User $user): JsonResponse
    {
        $loggedUser = Auth::user();

        $loggedUserIsAdmin = $loggedUser->role->name === 'admin';
        $loggedUserSearchesItself = $loggedUser->id === $user->id;
        if ($loggedUserIsAdmin || $loggedUserSearchesItself) {
            return new JsonResponse($user, Response::HTTP_OK);
        }

        return new JsonResponse('Not Found', Response::HTTP_OK);
    }

    public function update(User $user, UpdateUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $roleId = Role::where('name', 'member')->first()->id;
        if ($validated['is_admin']) {
            $roleId = Role::where('name', 'admin')->first()->id;
        }
        $user->role_id = $roleId;

        if (isset($validated['picture'])) {
            $picture = $validated['picture'];
            $storedFile = File::storeFile($picture);
            $user->avatar()->delete();
            $user->avatar()->save($storedFile);
        }

        $user->update($validated);
        $user->load('role');
        $user->load('avatar');

        return response()->json($user, Response::HTTP_OK);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function updateSubscriptionStatus(User $user, UpdateUserStatusRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user->active = $validated['active'];
        $user->update();
        $user->load('role');
        $user->load('avatar');

        return response()->json($user, Response::HTTP_OK);
    }

    public function changePassword(User $user, ChangePasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $loggedUser = Auth::user();

        $isCurrentPasswordCorrect = Hash::check($validated['current_password'], $loggedUser->password);
        if (!$isCurrentPasswordCorrect) {
            return response()->json('No autorizado', Response::HTTP_UNAUTHORIZED);
        }

        $loggedUserIsAdmin = $loggedUser->role->name === 'admin';
        if ($loggedUserIsAdmin || $loggedUser->id === $user->id) {
            $user->password = bcrypt($validated['password']);
            $user->update();
        }

        return response()->json('Contraseña actualizada', Response::HTTP_OK);
    }
}
