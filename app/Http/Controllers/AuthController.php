<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name'=>'required|string',
            'password'=>'required|string'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('customer_token',['customer_permissions'])->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'name'=>'required|string',
            'password'=>'required|string'
        ]);

        $user = User::where('name', $fields['name'])->first();
        if(!$user || !Hash::check($fields['password'], $user->password))
        {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }

        $tokenCan = 'customer_permissions';
        $token = $user->createToken('customer_token', [$tokenCan])->plainTextToken;

        $user['token'] = $token;
        $response = [
            'user' => $user,
            'tokenCan' => $tokenCan
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
