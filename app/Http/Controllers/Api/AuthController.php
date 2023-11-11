<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate(
            [
                "name" => 'required|string|max:100',
                'email' => 'required|email',
                'password' => 'required|min:8|max:20'
            ]
        );

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $token = $user->createToken('sanctum');
        return response()->json([
            'status' => 200,
            'message' => 'Successfully Registerd',
            'access-token' => $token->plainTextToken,
        ]);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // $user = auth()->user();
            /** @var \App\Models\User $user **/  $user = Auth::user();

            $token = $user->createToken('sanctum', ['user:show']);
            return response()->json([
                'status' => 200,
                'message' => 'Successfully Login',
                'access-token' => $token->plainTextToken,
            ]);
        }
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\User $user **/  $user = Auth::user();
        $user->tokens()->delete();

        return response()->json(['message' => 'Successfully Logout']);
    }
}
