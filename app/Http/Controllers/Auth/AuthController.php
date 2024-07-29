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
        //$permissions = $this->transformPermissions($user->role);
        //return $permissions;
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        
        $token = $user->createToken($user->role->name)->plainTextToken;

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }
    
        public function getPermissions(Request $request)
    {
        $user = $request->user();
        return $request;
        $token = $user->currentAccessToken();
        $permissions = $token->abilities; // Recuperar los permisos del token

        return response()->json(['permissions' => $permissions]);
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
            $data['role_id'] = 4;
            $user = User::create($data); 
            DB::commit();
            return response()->json(['message' => 'User registered successfully'], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'User registration failed', 'details' => $th->getMessage()], 500);
        }
        return response()->json(['message' => 'User registered successfully'], 201);
    }

    private function transformPermissions($permissions)
    {
        $transformed = [];
        foreach ($permissions as $permission) {
            $transformed[] = $permission->name; // Agregar el nombre del permiso al array
        }
        return implode(', ', $transformed); // Convertir el array en una cadena de texto
    }

}
