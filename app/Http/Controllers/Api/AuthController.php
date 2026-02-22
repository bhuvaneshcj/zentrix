<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error([
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return ApiResponse::success([
                'message' => 'User Registered Successfully',
                'token' => $token,
                'user' => $user,
            ], 201);
        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error([
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            if (! Auth::attempt($request->only('email', 'password'))) {
                return ApiResponse::error([
                    'message' => 'Invalid Credentials',
                ], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return ApiResponse::success([
                'message' => 'Login Successful',
                'token' => $token,
                'user' => $user,
            ], 200);
        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return ApiResponse::success([
                'message' => 'Logged out successfully',
            ]);
        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'Logout failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
