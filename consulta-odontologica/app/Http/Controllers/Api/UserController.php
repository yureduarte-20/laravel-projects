<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'string|required'
        ]);
        $user = User::where('email', $validated['email'])->with('roles')->first();
        if(!$user){
            return Response::json([ 'message' => 'Usuário não encontrado' ], 404);
        }
        if(Hash::check($validated['password'], $user->password)){
            return[ 'token' => $user->createToken('access_token', $user->roles->map(fn(Role $role) => $role->name)->toArray() )->plainTextToken];
        }
        throw ValidationException::withMessages([
            'password' => ['As credenciais fornecidas estao incorretas'],
        ]);
    }
}
