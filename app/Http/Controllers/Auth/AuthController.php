<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    /** 
     * Allows to register a new user
     * 
     * @var RegisterUserRequest $request
     * 
     * @return \Illuminate\Http\Response
     * */
    public function register(RegisterUserRequest $request)
    {
        $validated = $request->validated();
        $user = User::create([
            "name" => $validated['name'],
            'email' => $validated['email'],
            "password" => bcrypt($validated['password'])
        ]);

        $token = $user->createToken('aapdppToken')->plainTextToken;

        return response()->json([
            "user" => $user,
            "token" => $token
        ], 201);
    }

    /** 
     * Allows to login
     * 
     * @var LoginUserRequest $request
     * 
     * @return \Illuminate\Http\Response
     * */
    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();
        $user = User::where(['email' => $validated['email']])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json('Invalid credentials.', 401);
        }

        $user->tokens()->delete();
        $newToken = $user->createToken('aapdppToken')->plainTextToken;

        return response()->json([
            "user" => $user,
            "token" => $newToken
        ], 200);
    }


    /** 
     * Deletes all of the user's tokens
     * 
     * @return \Illuminate\Http\Response
     * */
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json('', 204);
    }

    /** 
     * Returns token's user
     * 
     * @return \Illuminate\Http\Response
     * */
    public function me()
    {
        return response()->json(auth()->user(), 200);
    }
}
