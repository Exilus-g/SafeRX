<?php

namespace App\Http\Controllers;

use App\Models\ErrorCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ErrorCategoryController extends Controller implements HasMiddleware
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
    public function index()
    {
        return ErrorCategory::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ErrorCategory $errorCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ErrorCategory $errorCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ErrorCategory $errorCategory)
    {
        //
    }
}
