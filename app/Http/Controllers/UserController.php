<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Muestra el perfil del usuario autenticado.
     */
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Actualiza el perfil del usuario.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // Validar datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'profile_photo_path' => 'nullable|string',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        if ($request->has('profile_photo_path')) {
            $user->profile_photo_path = $request->profile_photo_path;
        }

        $user->save();

        return response()->json($user);
    }
}
