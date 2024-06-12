<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function get_user(Request $request): JsonResponse
    {
        Log::info('User id: <{user_id}> request for user information', ['user_id' => $request->user()->id]);

        return response()->json([
            'message' => 'success',
            'user' => $request->user(),
        ], 200);
    }
}