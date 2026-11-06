<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function setup(Request $request)
    {
        if (User::count() > 0) {
            return response()->json(['message' => 'El sistema ya tiene un administrador configurado.'], 403);
        }

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'secret_word' => 'required|string|min:3'
        ]);

        $user = new User();
        $user->name = 'Administrador';
        $user->email = trim($request->email);
        $user->password = Hash::make(trim($request->password));
        // Encriptamos la palabra secreta en minúsculas para mayor seguridad y evitar errores de tipeo
        $user->secret_word = Hash::make(trim(strtolower($request->secret_word)));
        $user->save();

        return response()->json(['message' => '¡Administrador maestro creado con éxito!']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $emailLimpio = trim($request->email);
        $claveLimpia = trim($request->password);

        $user = User::where('email', $emailLimpio)->first();

        if (!$user || !Hash::check($claveLimpia, $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas. Verificá tu usuario y contraseña.'], 401);
        }

        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'token' => $token,
            'user' => $user
        ]);
    }

    // --- NUEVA FUNCIÓN: Recuperación de contraseña ---
    public function recover(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'secret_word' => 'required|string',
            'new_password' => 'required|min:6'
        ]);

        $user = User::where('email', trim($request->email))->first();
        $palabraLimpia = trim(strtolower($request->secret_word));

        // Usamos el mismo mensaje genérico para proteger la identidad del administrador
        if (!$user || !Hash::check($palabraLimpia, $user->secret_word)) {
            return response()->json(['message' => 'Los datos de recuperación son incorrectos.'], 401);
        }

        // Si todo coincide, actualizamos la contraseña y destruimos las llaves viejas
        $user->password = Hash::make(trim($request->new_password));
        $user->tokens()->delete(); // Obliga a volver a loguearse
        $user->save();

        return response()->json(['message' => 'Contraseña actualizada. Ya podés iniciar sesión.']);
    }
}
