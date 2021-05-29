<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthAPIController extends Controller
{
    /**
     * @OA\Post(
     * path="/v1/auth/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="postLogin",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Success login response",
     *     @OA\JsonContent(
     *          @OA\Property(property="status", type="boolean", example="true"),
     *          @OA\Property(property="message", type="string", example="Success to login"),
     *          @OA\Property(property="data", type="array",
     *              @OA\Items(
     *                  @OA\Property(property="token", type="string", example="xxx.xxxxx.xxx"),
     *                  @OA\Property(property="token_type", type="string", example="bearer"),
     *                  @OA\Property(property="expires_in", type="integer", example="86400"),
     *              ),
     *          )
     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="boolean", example="false"),
     *       @OA\Property(property="message", type="string", example="Invalid credentials")
     *        )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     * path="/v1/auth/logout",
     * summary="Logout",
     * description="Logout user and invalidate token",
     * operationId="postLogout",
     * tags={"auth"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *     @OA\JsonContent(
     *          @OA\Property(property="success", type="boolean", example="true"),
     *          @OA\Property(property="message", type="string", example="Your session is ended, now you can leave this site safely.")
     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Returns when user is not authenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example="false"),
     *       @OA\Property(property="errorMessage", type="string", example="Unauthenticated access"),
     *    )
     * )
     * )
     */
    public function postLogout(Request $request) {
        auth()->invalidate(true);

        return response()->json([
            'success' => true,
            'message' => 'Your session is ended, now you can leave this site safely.'
        ], 202);
    }
}
