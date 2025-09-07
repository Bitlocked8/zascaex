<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportestockController extends Controller
{
    public function generarPdf()
    {
        $stocks = Stock::all();
        $fecha = Carbon::now()->format('d/m/Y H:i');

        $pdf = Pdf::loadView('reportes.ReStock', compact('stocks', 'fecha'))->setPaper('A4', 'portrait');

        return $pdf->download('reporte_stock_' . date('Ymd_His') . '.pdf');
    }
}
