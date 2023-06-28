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
        $prest = \App\Prestamo::find($id);
        $pdf = Pdf::loadView('pdf.prestamo', ['prest' => $prest]);
        return $pdf->stream();
    }
}
