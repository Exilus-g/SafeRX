<?php

namespace App\Http\Controllers;

use App\Models\Factor;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class FactorController extends Controller implements HasMiddleware
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
     * Display a listing of the authenticated user's factors.
     * Retrieves the first factor associated with the authenticated user.
     * 
     * @param Request $request The current HTTP request instance
     * @return JSON response with factor data or a 404 error message
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (is_null($user->main_user_id)) {
            $factors = Factor::where('user_id', $user->id)->get();
        } else {
            $factors = Factor::where('user_id', $user->main_user_id)->get();
        }

        if ($factors->isNotEmpty()) {
            return response()->json($factors);
        } else {
            return response()->json(['message' => 'Aún no tienes datos registrados'], 404);
        }
    }

    /**
     * Store a newly created factor in storage.
     * Validates and saves a new factor associated with the authenticated user.
     * 
     * @param Request $request The current HTTP request instance
     * @return JSON response with the created factor data
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'factor_name' => 'required|string|max:255'
        ],[
            'factor_name.required' => 'El campo nombre del factor es obligatorio.',
            'factor_name.string' => 'El nombre debe ser una cadena de texto.',
            'factor_name.max' => 'El nombre no puede tener más de 255 caracteres.'
        ]);

        $factor = $request->user()->factors()->create($fields);

        return response()->json($factor);
    }

    /**
     * Display the specified factor.
     * Shows the details of a single factor after authorization check.
     * 
     * @param Factor $factor The factor instance to be displayed
     * @return JSON response with factor data
     */
    public function show(Factor $factor)
    {
        Gate::authorize('show', $factor);

        return response()->json($factor);
    }

    /**
     * Update the specified factor in storage.
     * Validates and updates an existing factor if the user is authorized.
     * 
     * @param Request $request The current HTTP request instance
     * @param Factor $factor The factor instance to be updated
     * @return JSON response with the updated factor data
     */
    public function update(Request $request, Factor $factor)
    {
        Gate::authorize('update', $factor);

        $fields = $request->validate([
            'factor_name' => 'required|string|max:255'
        ]);

        $factor->update($fields);

        return response()->json($factor);
    }

    /**
     * Remove the specified factor from storage.
     * Deletes a factor after authorization check.
     * 
     * @param Factor $factor The factor instance to be deleted
     * @return JSON response confirming deletion
     */
    public function destroy(Factor $factor)
    {
        Gate::authorize('destroy', $factor);

        $factor->delete();
        return response()->json(['message' => 'Datos borrados correctamente']);
    }
}
