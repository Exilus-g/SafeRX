<?php

namespace App\Http\Controllers;

use App\Models\ErrorType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class ErrorTypeController extends Controller implements HasMiddleware
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
     * Display a listing of the authenticated user's type.
     * Retrieves the first type associated with the authenticated user.
     * 
     * @param Request $request The current HTTP request instance
     * @return JSON response with type data or a 404 error message
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (is_null($user->main_user_id)) {
            $types = ErrorType::where('user_id', $user->id)->get();
        } else {
            $types = ErrorType::where('user_id', $user->main_user_id)->get();
        }

        if ($types->isNotEmpty()) {
            return response()->json($types);
        } else {
            return response()->json(['message' => 'Aún no tienes datos registrados'], 404);
        }
    }

    /**
     * Store a newly created type in storage.
     * Validates and saves a new type associated with the authenticated user.
     * 
     * @param Request $request The current HTTP request instance
     * @return JSON response with the created type data
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'type_name' => 'required|string|max:255'
        ],[
            'type_name.required' => 'El campo nombre del tipo es obligatorio.',
            'type_name.string' => 'El nombre debe ser una cadena de texto.',
            'type_name.max' => 'El nombre no puede tener más de 255 caracteres.'
        ]);

        $errorType = $request->user()->errorTypes()->create($fields);

        return response()->json($errorType);
    }

    /**
     * Display the specified type.
     * Shows the details of a single type after authorization check.
     * 
     * @param type $type The type instance to be displayed
     * @return JSON response with type data
     */
    public function show(ErrorType $errorType)
    {
        Gate::authorize('show', $errorType);

        return response()->json($errorType);
    }

    /**
     * Update the specified type in storage.
     * Validates and updates an existing type if the user is authorized.
     * 
     * @param Request $request The current HTTP request instance
     * @param type $type The type instance to be updated
     * @return JSON response with the updated type data
     */
    public function update(Request $request, ErrorType $errorType)
    {
        Gate::authorize('update', $errorType);

        $fields = $request->validate([
            'type_name' => 'required|string|max:255'
        ]);

        $errorType->update($fields);

        return response()->json($errorType);
    }

    /**
     * Remove the specified type from storage.
     * Deletes a type after authorization check.
     * 
     * @param type $type The type instance to be deleted
     * @return JSON response confirming deletion
     */
    public function destroy(ErrorType $errorType)
    {
        Gate::authorize('destroy', $errorType);

        $errorType->delete();
        return response()->json(['message' => 'Datos borrados correctamente']);
    }
}
