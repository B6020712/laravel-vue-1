<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Handle register new user
    */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            // 'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(3)],
        ]);

        if ($validator->fails()) {
            Log::error($validator->failed());
            return response()->json($validator->errors(), 400);
        }

        Log::info('Create new user for email: <{email}>', ['email' => $request->email]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (! $user) {
            Log::error('Something went wrong');
            return response()->json([
                "message" => "Can't create new user"
            ], 400);
        }

        return response()->json([
            "message" => "New user created",
            "user" => $user
        ], 201);
    }
    
    /**
     * Handle an authentication
     * receive to argument: $email and $password
    */
    public function authenticate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        Log::info('User email: <{email}> is trying to login from <{user_agent}> at '.Carbon::now()->format('Y-m-d H:i:s'), ['email' => $request->email, 'user_agent' => $request->header('User-Agent')]);

        $credentials = $request->only('email', 'password');
        
        if (! Auth::attempt($credentials)) {
            Log::error('User email: <{email}> is failed to authentication from <{user_agent}> at '.Carbon::now()->format('Y-m-d H:i:s'), ['email' => $request->email, 'user_agent' => $request->header('User-Agent')]);
            return response()->json(['message' => 'User not found'], 401);
        }
        
        $user = User::where('email', $request->email)->first();
        $token = $user->createToken($request->header('User-Agent'))->plainTextToken;

        Log::info('User email: <{email}> is pass authenticated from <{user_agent}> at '.Carbon::now()->format('Y-m-d H:i:s'), ['email' => $request->email, 'user_agent' => $request->header('User-Agent')]);
        
        return response()->json([
            'message' => 'Login Success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    /**
     * Handle logout 
     * 
    */
    public function sign_out(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            Log::info('User email: <{user}> trying to sign out', ['user' => $user->email]);
            $user->tokens()->delete();
            Log::info('User email: <{user}> singed out', ['user' => $user->email]);
    
            return response()->json([
                "message" => "Sign out"
            ], 204);
        } catch (\Exception $err) {
            Log::error("User credentials is invalid");
            Log::error("{err_msg}", ['err_msg' => $err]);
            // Log::error("{err_msg}", ['err_msg' => $err->getMessage()]);
            return response()->json([
                "message" => $err,
            ], 400);
        }
    }
}