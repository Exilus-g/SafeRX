<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CollaboratorController extends Controller implements HasMiddleware
{

    /**
     * Define the middleware for the controller.
     * Ensures that only authenticated users can access the routes.
     * 
     * @return Middleware Auth middleware with Sanctum
     */
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum')
        ];
    }
    
    /**
     * Display a listing of the collaborators for the authenticated user.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $collaborators = User::where('main_user_id', $user->id)->get();

        if ($collaborators->isNotEmpty()) {
            return response()->json($collaborators);
        } else {
            return response()->json(['message' => 'Aún no tienes colaboradores registrados'], 404);
        }
    }

    /**
     * Store a newly created user in the database after validating the request data.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
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

        
        $admin = $request->user();
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => $fields['password'], 
            'main_user_id' => $admin->id,
        ]);

        $user->assignRole('editor'); 

        return response()->json($user, 201);
    }

    /**
     * Display the specified collaborator.
     *
     * @param  int  $id
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        $user = $request->user();

        $collaborator = User::where('id', $id)
            ->where('main_user_id', $user->id)
            ->first();

        if ($collaborator) {
            return response()->json($collaborator);
        } else {
            return response()->json(['message' => 'Colaborador no encontrado o no tienes autorización para verlo'], 404);
        }
    }

    /**
     * Update the collaborator information based on the provided request data.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();

        $collaborator = User::where('id', $id)
            ->where('main_user_id', $user->id)
            ->first();

        if (!$collaborator) {
            return response()->json(['message' => 'Colaborador no encontrado o no tienes autorización para actualizarlo'], 403);
        }

        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $collaborator->id,
            'password'  => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
        ]);

        $collaborator->update($fields);

        return response()->json($collaborator);
    }

    /**
     * Delete a collaborator with the given ID if authorized.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = request()->user();

        $collaborator = User::where('id', $id)
            ->where('main_user_id', $user->id)
            ->first();

        if (!$collaborator) {
            return response()->json(['message' => 'Colaborador no encontrado o no tienes autorización para eliminarlo'], 403);
        }

        $collaborator->delete();

        return response()->json(['message' => 'Colaborador eliminado con éxito'], 200);
    }
}
