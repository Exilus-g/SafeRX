<?php

namespace App\Http\Controllers;

use App\Models\StaffInvolved;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class StaffInvolvedController extends Controller implements HasMiddleware
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
     * Display a listing of the authenticated user's staff.
     * Retrieves the first staff associated with the authenticated user.
     * 
     * @param Request $request The current HTTP request instance
     * @return JSON response with staff data or a 404 error message
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (is_null($user->main_user_id)) {
            $staff = StaffInvolved::where('user_id', $user->id)->get();
        } else {
            $staff = StaffInvolved::where('user_id', $user->main_user_id)->get();
        }

        if ($staff->isNotEmpty()) {
            return response()->json($staff);
        } else {
            return response()->json(['message' => 'Aún no tienes datos registrados'], 404);
        }
    }

    /**
     * Store a newly created staff in storage.
     * Validates and saves a new staff associated with the authenticated user.
     * 
     * @param Request $request The current HTTP request instance
     * @return JSON response with the created staff data
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'staff_name' => 'required|string|max:255'
        ],[
            'staff_name.required' => 'El campo nombre del personal es obligatorio.',
            'staff_name.string' => 'El nombre debe ser una cadena de texto.',
            'staff_name.max' => 'El nombre no puede tener más de 255 caracteres.'
        ]);

        $staffInvolved = $request->user()->staffInvolveds()->create($fields);

        return response()->json($staffInvolved);
    }

    /**
     * Display the specified staff.
     * Shows the details of a single staff after authorization check.
     * 
     * @param staff $staff The staff instance to be displayed
     * @return JSON response with staff data
     */
    public function show(StaffInvolved $staffInvolved)
    {
        Gate::authorize('show', $staffInvolved);

        return response()->json($staffInvolved);
    }

    /**
     * Update the specified staff in storage.
     * Validates and updates an existing staff if the user is authorized.
     * 
     * @param Request $request The current HTTP request instance
     * @param staff $staff The staff instance to be updated
     * @return JSON response with the updated staff data
     */
    public function update(Request $request, StaffInvolved $staffInvolved)
    {
        Gate::authorize('update', $staffInvolved);

        $fields = $request->validate([
            'staff_name' => 'required|string|max:255'
        ]);

        $staffInvolved->update($fields);

        return response()->json($staffInvolved);
    }

    /**
     * Remove the specified staff from storage.
     * Deletes a staff after authorization check.
     * 
     * @param staff $staff The staff instance to be deleted
     * @return JSON response confirming deletion
     */
    public function destroy(StaffInvolved $staffInvolved)
    {
        Gate::authorize('destroy', $staffInvolved);

        $staffInvolved->delete();
        return response()->json(['message' => 'Datos borrados correctamente']);
    }
}
