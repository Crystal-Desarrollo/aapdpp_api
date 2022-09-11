<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserStatusRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::with('role')->where('email', '<>', 'crystaldesarrollo@gmail.com')->orderBy('id', 'ASC')->get(), 200);
    }

    public function show(User $user)
    {
        $loggedUser = Auth::user();

        $loggedUserIsAdmin = $loggedUser->role->name === 'admin';
        $loggedUserSearchesItself = $loggedUser->id === $user->id;
        if ($loggedUserIsAdmin || $loggedUserSearchesItself) {
            return response($user, 200);
        }

        return response('Not Found', 404);
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $validated = $request->validated();

        $roleId = Role::where('name', 'member')->first()->id;
        if (boolval($validated['is_admin'])) {
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

        return response()->json($user, 200);
    }

    public function delete(User $user)
    {
        $user->delete();
        return response()->json('', 204);
    }

    public function updateSubscriptionStatus(User $user, UpdateUserStatusRequest $request)
    {
        $validated = $request->validated();
        $user->active = $validated['active'];
        $user->update();
        $user->load('role');
        $user->load('avatar');

        return response()->json($user, 200);
    }

    public function changePassword(User $user, ChangePasswordRequest $request)
    {
        $validated = $request->validated();
        $loggedUser = Auth::user();

        $isCurrentPasswordCorrect = Hash::check($validated['current_password'], $loggedUser->password);
        if (!$isCurrentPasswordCorrect) {
            return response('Unhautorized', 401);
        }

        $loggedUserIsAdmin = $loggedUser->role->name === 'admin';
        if ($loggedUserIsAdmin || $loggedUser->id === $user->id) {
            $user->password = bcrypt($validated['password']);
            $user->update();
        }

        return response()->json('Password updated', 200);
    }
}
