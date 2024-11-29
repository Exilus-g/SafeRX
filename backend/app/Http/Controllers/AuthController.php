<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    /**
     * Register a new user based on the provided request data.
     *
     * @param Request $request The HTTP request containing user registration data.
     * @return array An array containing user information, roles, and authentication token.
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => [
                'required', 
                'confirmed', 
                'min:8', 
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'Debe proporcionar un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
        
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.'
        ]);
        
        $user = User::create($fields);
        $user->assignRole('admin');
        $token = $user->createToken($request->name);
        
        return [
            'user' => $user->load('roles'), // Carga el rol del usuario
            'roles' => $user->getRoleNames(), // Retorna los roles del usuario
            'token' => $token->plainTextToken,
        ];
    }
    
    /**
     * Logs in a user based on the provided request data.
     *
     * @param Request $request The request containing user login information.
     * @return array An array containing user information and authentication token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255|exists:users',
            'password' => 'required',
        ], [
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no tiene un formato válido.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'email.exists' => 'El correo electrónico no está registrado.',
            'password.required' => 'El campo de contraseña es obligatorio.',
        ]);
        
        $user = User::where('email',$request->email)->first();
        
        if(!$user || !Hash::check($request->password,$user->password)){
            return [
                'errors' => [
                    'email' => [
                        'Las credenciales son incorrectas'
                    ]
                ]
            ];
        }


        $token = $user->createToken($user->name);
        return [
            'user' => $user->load('roles'),
            'roles' => $user->getRoleNames(),
            'token' => $token->plainTextToken,
        ];
    }

    
    /**
     * Logs out the authenticated user by deleting all of the user's tokens.
     *
     * @param Request $request The request object containing the authenticated user
     * @return array An array with a success message indicating that the user has logged out successfully
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return [
            'message' => 'Ha cerrado sesión correctamente.'
        ];
    }
}
