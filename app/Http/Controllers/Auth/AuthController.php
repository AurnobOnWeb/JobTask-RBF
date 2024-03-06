<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest\LoginUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->only(['email', 'password']));

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('', 'Credentials do not match', 401);
        }
        $user = User::where('email', $request->email)->first();

        return $this->successResponse([
            'user' => $user,
            'token' => $user->createToken('API Token of' . $user->name)->plainTextToken
        ]);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->successResponse([
            'message' => 'You have successfully Loged Out'
        ]);
    }
}
