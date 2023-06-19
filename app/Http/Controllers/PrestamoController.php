<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PrestamoController extends Controller
{
    public function prestamo_store(Request $request)
    {
        return response()->json($request);
    }

    public function pdf_prestamo($id)
    {
        
        $pdf = Pdf::loadView('pdf.prestamo', ['id' => $id]);
        return $pdf->stream();
    }
}
