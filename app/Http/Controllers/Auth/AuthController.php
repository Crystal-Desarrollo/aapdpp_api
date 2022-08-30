<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Mail\FirstLoginMail;
use App\Models\File;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
        $user = User::make($validated);

        $temporalPass = Str::random();
        $user->password = bcrypt($temporalPass);

        $roleId = Role::where('name', 'member')->first()->id;
        if (boolval($validated['is_admin'])) {
            $roleId = Role::where('name', 'admin')->first()->id;
        }
        $user->role_id = $roleId;
        $user->save();

        if (isset($validated['picture'])) {
            $picture = $validated['picture'];
            $storedFile = File::storeFile($picture);
            $user->avatar()->save($storedFile);
        }

        $user->load('avatar');
        $user->load('role');

        $mail = new FirstLoginMail($user, $temporalPass);
        Mail::to($validated['email'])->send($mail);

        return response()->json($user, 201);
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
            return response()->json('Usuario o contraseña incorrecto', 401);
        }

        $user->tokens()->delete();
        $newToken = $user->createToken('aapdppToken')->plainTextToken;
        $user->load('role');

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
        $user = auth()->user();
        $user->load('role');
        return response()->json($user, 200);
    }
}
