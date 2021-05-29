<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthAPIController extends Controller
{
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|String'
        ]);

        if (!$token = Auth::attempt($request->only(['email', 'password'])))
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);

        return $this->respondWithToken($token);
    }

    public function postLogout(Request $request) {
        auth()->invalidate(true);

        return response()->json([
            'success' => true,
            'message' => 'Your session is ended, now you can leave this site safely.'
        ], 202);
    }
}
