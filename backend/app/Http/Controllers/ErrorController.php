<?php

namespace App\Http\Controllers;

use App\Models\Error;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class ErrorController extends Controller implements HasMiddleware
{
    /*
    *static function middleware
    *Create Auth routes
    !Return authentication with sanctum
    */
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum')
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (is_null($user->main_user_id)) {
            $errors = Error::with([
                'place:id,place_name',
                'staffInvolved:id,staff_name',
                'factor:id,factor_name',
                'errorCategory:id,category_name',
                'errorTypes:id,type_name',
                'node:id,process'
            ])->where('user_id', $user->id)->get();
        } else {
            $errors = Error::with([
                'place:id,place_name',
                'staffInvolved:id,staff_name',
                'factor:id,factor_name',
                'errorCategory:id,category_name',
                'errorTypes:id,type_name',
            ])->where('user_id', $user->main_user_id)->get();
        }

        if ($errors->isNotEmpty()) {
            return response()->json($errors);
        } else {
            return response()->json(['message' => 'Aún no tienes datos registrados'], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // Determinar el ID del usuario principal
        $userId = $user->main_user_id ?? $user->id;

        // Validar los campos del error
        $fields = $request->validate([
            'error_date' => 'required|date',
            'report_date' => 'required|date',
            'patient_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'area' => 'required|string|max:255',
            'folder' => 'required|string|max:255',
            'description' => 'required|string',
            'node_id' => 'required|exists:nodes,id',
            'place_id' => 'required|exists:places,id',
            'staff_involved_id' => 'required|exists:staff_involveds,id',
            'factor_id' => 'required|exists:factors,id',
            'error_category_id' => 'required|exists:error_categories,id',
            'error_type_id' => 'required|array',
            'error_type_id.*' => 'exists:error_types,id',
        ], [
            'error_date.required' => 'La fecha del error es obligatoria.',
            'error_date.date' => 'La fecha del error debe tener un formato válido.',
        
            'report_date.required' => 'La fecha del reporte es obligatoria.',
            'report_date.date' => 'La fecha del reporte debe tener un formato válido.',
        
            'patient_name.required' => 'El nombre del paciente es obligatorio.',
            'patient_name.string' => 'El nombre del paciente debe ser un texto válido.',
            'patient_name.max' => 'El nombre del paciente no puede exceder los 255 caracteres.',
        
            'date_of_birth.required' => 'La fecha de nacimiento es obligatoria.',
            'date_of_birth.date' => 'La fecha de nacimiento debe tener un formato válido.',
        
            'area.required' => 'El área es obligatoria.',
            'area.string' => 'El área debe ser un texto válido.',
            'area.max' => 'El área no puede exceder los 255 caracteres.',

            'folder.required' => 'El expediente es obligatorio.',
            'folder.string' => 'La expediente debe ser un texto válido.',
            'folder.max' => 'La expediente no puede exceder los 255 caracteres.',
        
            'description.required' => 'La descripción es obligatoria.',
            'description.string' => 'La descripción debe ser un texto válido.',
        
            'node_id.required' => 'El proceso asociado es obligatorio.',
            'node_id.exists' => 'El proceso seleccionado no existe.',
        
            'place_id.required' => 'El lugar asociado es obligatorio.',
            'place_id.exists' => 'El lugar seleccionado no existe.',
        
            'staff_involved_id.required' => 'El personal involucrado es obligatorio.',
            'staff_involved_id.exists' => 'El personal seleccionado no existe.',
        
            'factor_id.required' => 'El factor asociado es obligatorio.',
            'factor_id.exists' => 'El factor seleccionado no existe.',
        
            'error_category_id.required' => 'La categoría del error es obligatoria.',
            'error_category_id.exists' => 'La categoría del error seleccionada no existe.',
        
            'error_type_id.required' => 'Debes seleccionar al menos un tipo de error.',
            'error_type_id.array' => 'El tipo de error debe enviarse como un arreglo.',
            'error_type_id.*.exists' => 'Uno o más tipos de error seleccionados no existen.',
        ]);
        

        // Crear el error asociado al usuario principal
        $error = Error::create([
            'error_date' => $fields['error_date'],
            'report_date' => $fields['report_date'],
            'patient_name' => $fields['patient_name'],
            'date_of_birth' => $fields['date_of_birth'],
            'area' => $fields['area'],
            'folder' => $fields['folder'],
            'description' => $fields['description'],
            'node_id' => $fields['node_id'],
            'place_id' => $fields['place_id'],
            'staff_involved_id' => $fields['staff_involved_id'],
            'factor_id' => $fields['factor_id'],
            'error_category_id' => $fields['error_category_id'],
            'user_id' => $userId, // Asociar al usuario principal
        ]);

        // Asociar los tipos de error al registro creado
        $error->errorTypes()->sync($fields['error_type_id']);

        return response()->json([
            'message' => 'Error registrado exitosamente.',
            'error' => $error,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Error $error)
    {
        // Autorizar el acceso al error
        Gate::authorize('show', $error);

        // Devolver el error autorizado con sus relaciones cargadas
        return response()->json($error->load([
            'place:id,place_name',
            'staffInvolved:id,staff_name',
            'factor:id,factor_name',
            'errorCategory:id,category_name',
            'errorTypes:id,type_name',
            'node:id,process'
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Error $error)
    {
        // Autorizar el acceso al error
        Gate::authorize('update', $error);

        // Validar los campos de la actualización
        $fields = $request->validate([
            'error_date' => 'required|date',
            'report_date' => 'required|date',
            'patient_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'area' => 'required|string|max:255',
            'folder' => 'required|string|max:255',
            'description' => 'required|string',
            'node_id' => 'required|exists:nodes,id',
            'place_id' => 'required|exists:places,id',
            'staff_involved_id' => 'required|exists:staff_involveds,id',
            'factor_id' => 'required|exists:factors,id',
            'error_category_id' => 'required|exists:error_categories,id',
            'error_type_id' => 'required|array',
            'error_type_id.*' => 'exists:error_types,id',
        ], [
            'error_date.required' => 'La fecha del error es obligatoria.',
            'error_date.date' => 'La fecha del error debe tener un formato válido.',
        
            'report_date.required' => 'La fecha del reporte es obligatoria.',
            'report_date.date' => 'La fecha del reporte debe tener un formato válido.',
        
            'patient_name.required' => 'El nombre del paciente es obligatorio.',
            'patient_name.string' => 'El nombre del paciente debe ser un texto válido.',
            'patient_name.max' => 'El nombre del paciente no puede exceder los 255 caracteres.',
        
            'date_of_birth.required' => 'La fecha de nacimiento es obligatoria.',
            'date_of_birth.date' => 'La fecha de nacimiento debe tener un formato válido.',
        
            'area.required' => 'El área es obligatoria.',
            'area.string' => 'El área debe ser un texto válido.',
            'area.max' => 'El área no puede exceder los 255 caracteres.',
            
            'folder.required' => 'El expediente es obligatorio.',
            'folder.string' => 'La expediente debe ser un texto válido.',
            'folder.max' => 'La expediente no puede exceder los 255 caracteres.',
        
            'description.required' => 'La descripción es obligatoria.',
            'description.string' => 'La descripción debe ser un texto válido.',
        
            'node_id.required' => 'El proceso asociado es obligatorio.',
            'node_id.exists' => 'El proceso seleccionado no existe.',
        
            'place_id.required' => 'El lugar asociado es obligatorio.',
            'place_id.exists' => 'El lugar seleccionado no existe.',
        
            'staff_involved_id.required' => 'El personal involucrado es obligatorio.',
            'staff_involved_id.exists' => 'El personal seleccionado no existe.',
        
            'factor_id.required' => 'El factor asociado es obligatorio.',
            'factor_id.exists' => 'El factor seleccionado no existe.',
        
            'error_category_id.required' => 'La categoría del error es obligatoria.',
            'error_category_id.exists' => 'La categoría del error seleccionada no existe.',
        
            'error_type_id.required' => 'Debes seleccionar al menos un tipo de error.',
            'error_type_id.array' => 'El tipo de error debe enviarse como un arreglo.',
            'error_type_id.*.exists' => 'Uno o más tipos de error seleccionados no existen.',
        ]);
        

        // Actualizar el registro de error
        $error->update([
            'error_date' => $fields['error_date'],
            'report_date' => $fields['report_date'],
            'patient_name' => $fields['patient_name'],
            'date_of_birth' => $fields['date_of_birth'],
            'area' => $fields['area'],
            'folder' => $fields['folder'],
            'description' => $fields['description'],
            'node_id' => $fields['node_id'],
            'place_id' => $fields['place_id'],
            'staff_involved_id' => $fields['staff_involved_id'],
            'factor_id' => $fields['factor_id'],
            'error_category_id' => $fields['error_category_id'],
        ]);

        // Actualizar los tipos de error asociados
        $error->errorTypes()->sync($fields['error_type_id']);

        return response()->json([
            'message' => 'Error actualizado exitosamente.',
            'error' => $error->load([
                'place:id,place_name',
                'staffInvolved:id,staff_name',
                'factor:id,factor_name',
                'errorCategory:id,category_name',
                'errorTypes:id,type_name',
                'node:id,process'
            ]),
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Error $error)
    {
        Gate::authorize('destroy', $error);

        $error->delete();
        return response()->json(['message' => 'Datos borrados correctamente']);
    }
}
