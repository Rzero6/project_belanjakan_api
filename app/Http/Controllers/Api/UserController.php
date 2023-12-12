<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function base64_to_jpeg($base64_string, $output_file)
    {
        $file = base64_decode($base64_string);
        $img_file = public_path('/images/user') . "/$output_file";
        file_put_contents($img_file, $file);
    }

    public function show()
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);
        if (is_null($user)) {
            return response()->json([
                "message" => "User not Found",
                "data" => null,
            ], 404);
        }

        return response()->json([
            "message" => "User found",
            "data" => $user,
        ], 200);
    }

    public function showById($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json([
                "message" => "User not Found",
                "data" => null,
            ], 404);
        }

        return response()->json([
            "message" => "User found",
            "data" => $user,
        ], 200);
    }

    public function update(Request $request)
    {
        $updatedUserData = $request->only(['name', 'email', 'password', 'phone', 'profile_picture']);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $userId = $user->id;

        $userToUpdate = User::find($userId);
        if (!$userToUpdate) {
            return response()->json(['message' => 'User not Found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('users')->ignore($userToUpdate->id),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        if ($request->has('password')) {
            $updatedUserData['password'] = bcrypt($request->password);
        }

        if ($request->has('profile_picture')) {
            $imageName = time() . '.jpg';
            $this->base64_to_jpeg($request->profile_picture, $imageName);
            $updatedUserData['profile_picture'] = '/images/user/' . $imageName;
            if ($userToUpdate->profile_picture !== null && file_exists(public_path($userToUpdate->profile_picture))) {
                unlink(public_path($userToUpdate->profile_picture));
            }
        }

        // Update only provided fields
        foreach ($updatedUserData as $key => $value) {
            if ($value !== null) {
                $userToUpdate->$key = $value;
            }
        }

        if ($userToUpdate->save()) {
            return response()->json(['message' => 'Update User Success', 'data' => $userToUpdate], 200);
        }

        return response()->json(['message' => 'Update User Failed', 'data' => null], 400);
    }
}
