<?php

namespace App\Http\Controllers\Api;

use App\Enum\RolesEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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
            'password' => 'string|required',
        ], [
            'email.email' => 'Precisa ser um email',
            'email.exists' => 'Usuário não encontrado',
            'password' => 'Senha obrigatória',
        ]);
        $user = User::where('email', $validated['email'])->with('roles')->first();
        if (Hash::check($validated['password'], $user->password)) {
            return ['token' => $user->createToken('access_token', $user->roles->map(fn (Role $role) => $role->name)->toArray())->plainTextToken];
        }
        throw ValidationException::withMessages([
            'password' => ['As credenciais fornecidas estao incorretas'],
        ]);
    }

    public function signup(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'password' => 'required|confirmed|string|min:8',
            'email' => 'required|email|unique:users',
        ], [
            'name' => [
                'string' => 'O nome precisa ser um texto.',
                'min' => 'É necessário no mínimo 3 caracteres.',
                'required' => 'O campo nome é obrigatório.',
            ],
            'password' => [
                'confirmed' => 'As senhas não coicidem.',
                'string' => 'A senha precisa ser um texto.',
                'min' => 'A senha precisa ter no mínimo 8 caracteres.',
            ],
            'email' => [
                'unique' => 'Este email já esta sendo utilizado.',
                'required' => 'O email é obrigatório.',
            ],
        ]);
        $user = User::create($validated);
        $user->assignRole(RolesEnum::PACIENTE->name);

        return Response::json(['token' => $user->createToken('access_token',
            $user->roles->map(fn (Role $role) => $role->name)->toArray())->plainTextToken], 201);
    }
}
