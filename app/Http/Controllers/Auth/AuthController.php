<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function register(RegisterRequest $request)
    {
        //return "here";
        DB::beginTransaction();
        try {
            $existingUser = User::where('email', $request->email)->orWhere('dni', $request->dni)->first();
            if ($existingUser) {
                return response()->json(['error' => 'User with this email or DNI already exists'], 409);
            }
            $data = $request->only(['name', 'email', 'dni', 'password', 'phone', 'address']);
            $data['password'] = Hash::make($data['password']);
            
            $user = User::create($data); 
            DB::commit();
            return response()->json(['message' => 'User registered successfully'], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'User registration failed', 'details' => $th->getMessage()], 500);
        }
        return response()->json(['message' => 'User registered successfully'], 201);
    }
}
