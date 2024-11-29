<?php

namespace App\Http\Controllers;

use App\Models\Diagram;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DiagramController extends Controller implements HasMiddleware
{
    
    /**
     * Get the middleware for the application.
     *
     * @return array
     */
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum')
        ];
    }

    
    /**
     * Display the diagram for the authenticated user.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $diagram = Diagram::where('user_id', $user->id)->first();

        if ($diagram) {
            return response()->json(['json_data' => $diagram->json_data]);
        } else {
            return response()->json(['message' => 'Diagrama no encontrado'], 404);
        }
    }

    
    /**
     * Store a new diagram or update an existing diagram for the authenticated user.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'json_data' => 'required|json',
        ]);
        $user = $request->user();

        $existingDiagram = $user->diagrams()->first();

        if ($existingDiagram) {
            $existingDiagram->json_data = $fields['json_data'];
            $existingDiagram->save();
            return response()->json(['message' => 'Diagrama actualizado exitosamente.', 'diagram' => $existingDiagram]);
        } else {
            $diagram = $user->diagrams()->create($fields);
            return response()->json(['message' => 'Diagrama guardado exitosamente.', 'diagram' => $diagram]);
        }
    }

    
    /**
     * Display a JSON response with a message indicating that the route is not available.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        return response()->json([
            'message' => 'Esta ruta no está disponible.'
        ], 405);
    }
    

    /**
     * Display a JSON response with a message indicating that the route is not available.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Diagram $diagram)
    {
        return response()->json([
            'message' => 'Esta ruta no está disponible.'
        ], 405);
    }

    
    /**
     * Display a JSON response with a message indicating that the route is not available.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Diagram $diagram)
    {
        return response()->json([
            'message' => 'Esta ruta no está disponible.'
        ], 405);
    }
}
