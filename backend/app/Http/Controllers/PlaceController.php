<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class PlaceController extends Controller implements HasMiddleware
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
     * Display a listing of the authenticated user's places.
     * Retrieves the first place associated with the authenticated user.
     * 
     * @param Request $request The current HTTP request instance
     * @return JSON response with place data or a 404 error message
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (is_null($user->main_user_id)) {
            $places = Place::where('user_id', $user->id)->get();
        } else {
            $places = Place::where('user_id', $user->main_user_id)->get();
        }

        if ($places->isNotEmpty()) {
            return response()->json($places);
        } else {
            return response()->json(['message' => 'Aún no tienes datos registrados'], 404);
        }
    }





    /**
     * Store a newly created place in storage.
     * Validates and saves a new place associated with the authenticated user.
     * 
     * @param Request $request The current HTTP request instance
     * @return JSON response with the created place data
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'place_name' => 'required|string|max:255'
        ], [
            'place_name.required' => 'El campo nombre del lugar es obligatorio.',
            'place_name.string' => 'El nombre debe ser una cadena de texto.',
            'place_name.max' => 'El nombre no puede tener más de 255 caracteres.'
        ]);

        $place = $request->user()->places()->create($fields);

        return response()->json($place);
    }

    /**
     * Display the specified place.
     * Shows the details of a single place after authorization check.
     * 
     * @param Place $place The place instance to be displayed
     * @return JSON response with place data
     */
    public function show(Place $place)
    {
        Gate::authorize('show', $place);

        return response()->json($place);
    }

    /**
     * Update the specified place in storage.
     * Validates and updates an existing place if the user is authorized.
     * 
     * @param Request $request The current HTTP request instance
     * @param Place $place The place instance to be updated
     * @return JSON response with the updated place data
     */
    public function update(Request $request, Place $place)
    {
        Gate::authorize('update', $place);

        $fields = $request->validate([
            'place_name' => 'required|string|max:255'
        ]);

        $place->update($fields);

        return response()->json($place);
    }

    /**
     * Remove the specified place from storage.
     * Deletes a place after authorization check.
     * 
     * @param Place $place The place instance to be deleted
     * @return JSON response confirming deletion
     */
    public function destroy(Place $place)
    {
        Gate::authorize('destroy', $place);

        $place->delete();
        return response()->json(['message' => 'Datos borrados correctamente']);
    }
}
