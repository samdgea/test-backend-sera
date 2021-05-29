<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Info(
 *    title="Abdilah's Backend API Test",
 *    version="1.0.0",
 * )
 */
class Controller extends BaseController
{
    protected function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'message' => 'Success to login',
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60
            ]
        ], 200);
    }
}
