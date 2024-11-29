<?php

namespace App\Http\Controllers;

use App\Models\Node;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class NodeController extends Controller implements HasMiddleware
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
     * Display the factors for the authenticated user based on the request.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (is_null($user->main_user_id)) {
            $factors = Node::where('user_id', $user->id)->get();
        } else {
            $factors = Node::where('user_id', $user->main_user_id)->get();
        }

        if ($factors->isNotEmpty()) {
            return response()->json($factors);
        } else {
            return response()->json(['message' => 'Aún no tienes datos registrados'], 404);
        }
    }

    
    /**
     * Store the texts provided in the request after validating them.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $user = $request->user();

        $validated = $request->validate([
            'texts' => 'required',
            'texts.*' => 'required|string|max:255',
        ]);

        // Obtener los nodos actuales del usuario
        $existingNodes = Node::where('user_id', $user->id)->get();

        // Obtener los textos recibidos
        $incomingTexts = $validated['texts'];

        // Encontrar los nodos que ya no están en el nuevo arreglo y eliminarlos
        $deletedNodes = $existingNodes->filter(function ($node) use ($incomingTexts) {
            return !in_array($node->process, $incomingTexts);
        });

        // Eliminar los nodos que ya no están
        foreach ($deletedNodes as $node) {
            $node->delete();
        }

        // Guardar los nuevos nodos
        $savedNodes = [];
        foreach ($incomingTexts as $text) {
            $existingNode = $existingNodes->firstWhere('process', $text);

            // Si no existe un nodo con ese texto, se guarda uno nuevo
            if (!$existingNode) {
                $node = Node::create([
                    'user_id' => $user->id,
                    'process' => $text,
                ]);
                $savedNodes[] = $node;
            }
        }

        return response()->json([
            'message' => 'Procesos guardados correctamente',
            'data' => $savedNodes,
        ]);
    }

    
    /**
     * Show a JSON response with a message indicating that the route is not available.
     *
     * @param Node $node
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Node $node)
    {
        return response()->json([
            'message' => 'Esta ruta no está disponible.'
        ], 405);
    }

    /**
     * Show a JSON response with a message indicating that the route is not available.
     *
     * @param Node $node
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Node $node)
    {
        return response()->json([
            'message' => 'Esta ruta no está disponible.'
        ], 405);
    }

    /**
     * Show a JSON response with a message indicating that the route is not available.
     *
     * @param Node $node
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Node $node)
    {
        return response()->json([
            'message' => 'Esta ruta no está disponible.'
        ], 405);
    }
}
