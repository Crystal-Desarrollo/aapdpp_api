<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::with('role')->get(), 200);
    }

    public function update(User $user, UpdateUserRequest $request)
    {

        $validated = $request->validated();

        $user->update($validated);
        $user->load('role');

        return response()->json($user, 200);
    }

    public function delete(User $user)
    {
        $user->delete();
        return response()->json('', 204);
    }
}
