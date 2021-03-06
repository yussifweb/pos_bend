<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'username' => 'required|max:8',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // $token = $user->createToken($user->email . '_Token', [''])->plainTextToken;

            return response()->json([
                'status' => 200,
                // 'username' => $user->name,
                // 'token' => $token,
                'message' => 'Registration Successful!'
            ]);
        }
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:191',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ]);
        } else {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid Credentials',
                ]);
            } else {
                if ($user->role_as == 2) {
                    $role = 'admin';
                    $token = $user->createToken($user->email . '_AdminToken', ['server:admin'])->plainTextToken;
                } elseif ($user->role_as == 1) {
                    $role = 'owner';
                    $token = $user->createToken($user->email . '_OwnerToken', ['server:owner'])->plainTextToken;
                } else {
                    $role = '';
                    $token = $user->createToken($user->email . '_Token', [''])->plainTextToken;
                }

                return response()->json([
                    'status' => 200,
                    'username' => $user->username,
                    'token' => $token,
                    'message' => 'Login Successful!',
                    'role' => $role,
                ]);
            }
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Logout Successful!',
        ]);
    }
}
