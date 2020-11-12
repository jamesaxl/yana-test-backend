<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class PassPortController extends Controller
{
    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('IamVerySecret')->accessToken;

            if (!auth()->user()->is_enabled) {
                return response()->errorAuth('user is not enabled');
            }

            $user = User::with('role')->find(auth()->user()->id);
            return response()->json(
                [
                    'error' => 0,
                    'user' => $user,
                    'token' => $token
                ], 200);
        }

        return response()->errorAuth('Check your email and password');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        return response()->ok([], 'You have been successfully logged out!');
    }
}
