<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function update(UpdateUserRequest $request)
    {

        $validated = $request->validated();

        $user = Auth::user();

        $user->update($validated);

        return response()->json($user, 200);
    }
}
