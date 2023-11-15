<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $str = Str::random(100);
        $registrationData = $request->all();
        $registrationData['verify_key'] = $str;
        $validate = Validator::make($registrationData, [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required',
            'phone' => 'required',
            'date_of_birth' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['message' => $validate->errors()], 400);
        }
        $registrationData['password'] = bcrypt($request->password);

        $user = User::create($registrationData);

        return response()->json([
            'message' => 'Register Success',
            'user' => $user
        ], 200);
    }
    public function login(Request $request)
    {
        $loginData = $request->all();

        $validate = Validator::make($loginData, [
            "email" => "required|email:rfc,dns",
            "password" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors(),
            ], 400);
        }

        if (!Auth::attempt($loginData)) {
            return response()->json([
                'message' => 'Email atau Password salah',
            ], 401);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken;

        return response()->json([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token,
        ]);
    }
}
