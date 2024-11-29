<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiagramController;
use App\Http\Controllers\ErrorCategoryController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\ErrorTypeController;
use App\Http\Controllers\FactorController;
use App\Http\Controllers\NodeController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\StaffInvolvedController;
use App\Http\Controllers\CollaboratorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Define a route that returns the authenticated user information and their roles in JSON format.
 *
 * The route expects a Request object as a parameter.
 * It retrieves the authenticated user using the user() method on the Request object.
 * It then constructs a JSON response containing the user information and their roles.
 * The roles are obtained using the getRoleNames() method on the user object.
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
Route::get('/user', function (Request $request) {
    $user = $request->user();
    return response()->json([
        'user' => $user,
        'roles' => $user->getRoleNames(),
    ]);
})->middleware('auth:sanctum');



/**
 * Define API resource routes for the specified controllers.
 *
 * - 'places' => PlaceController
 * - 'staff_involveds' => StaffInvolvedController
 * - 'factors' => FactorController
 * - 'error_types' => ErrorTypeController
 * - 'collaborators' => CollaboratorController (with 'auth:sanctum' middleware)
 * - 'errors' => ErrorController
 * - 'error_categories' => ErrorCategoryController
 * - 'diagrams' => DiagramController
 * - 'nodes' => NodeController
 */
Route::apiResource('places', PlaceController::class);
Route::apiResource('staff_involveds', StaffInvolvedController::class);
Route::apiResource('factors', FactorController::class);
Route::apiResource('error_types', ErrorTypeController::class);
Route::apiResource('collaborators', CollaboratorController::class)->middleware('auth:sanctum');
Route::apiResource('errors', ErrorController::class);
Route::apiResource('error_categories', ErrorCategoryController::class);
Route::apiResource('diagrams', DiagramController::class);
Route::apiResource('nodes', NodeController::class);



/**
 * Define the routes for user authentication.
 *
 * Route::post('/register', [AuthController::class, 'register']);
 * Route::post('/login', [AuthController::class, 'login']);
 * Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
 */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
