<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    public function prestamo_store(Request $request)
    {
        return response()->json($request);
    }
}
