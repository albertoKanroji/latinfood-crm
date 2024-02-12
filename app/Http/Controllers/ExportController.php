<?php

namespace App\Http\Controllers;

use App\Exports\SalesExport;
use App\Models\Sale;
use App\Models\Lotes;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Inspectors;
use App\Models\SaleDetail;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ExportController extends Controller
{

    public function historial($cliente_id)
    {
        $cliente = Customer::find($cliente_id);

        if ($cliente) {
            $ventas = $cliente->sale;

            $pdf = PDF::loadView('pdf.historial', compact('ventas', 'cliente'));
            $user = Auth()->user()->name;
            $inspector = Inspectors::create([
                'user' => $user,
                'action' => 'Imprimio Historial',
                'seccion' => 'Reportes'
            ]);
            return $pdf->stream('historial.pdf');
        } else {
            // Manejar el caso cuando el cliente no existe
            $user = Auth()->user()->name;
            $inspector = Inspectors::create([
                'user' => $user,
                'action' => 'intento imprimir Historial de un cliente descocido',
                'seccion' => 'Reportes'
            ]);
            return "El cliente con ID $cliente_id no fue encontrado";
        }
    }

    public function InspectorsPDF()
    {
        $data = Inspectors::all(); // Obtén todos los registros

        $pdf = PDF::loadView('pdf.inspectors', compact('data')); // Carga la vista del PDF y pasa los datos necesarios

        return $pdf->download('Logs_For_Inspectors.pdf');
    }





    public function detail($id)
    {

        $data = Product::find($id);
        $lot = Lotes::find($data->id);
        $prod = Product::where('id', $data->id)->first();
        $qr =  QrCode::size(210)->generate($data->KeyProduct);
        $hora = now()->format('d/m/Y h:i A');


        $pdf = PDF::loadView('pdf.lote', compact('data', 'lot', 'prod', 'qr', 'hora'));
        return $pdf->stream($data->name . '.pdf');
    }

    public function reportPDF($userId, $reportType, $dateFrom = null, $dateTo = null)
    {
        $data = [];

        if ($reportType == 0) // ventas del dia
        {
            $from = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse(Carbon::now())->format('Y-m-d')   . ' 23:59:59';
        } else {
            $from = Carbon::parse($dateFrom)->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse($dateTo)->format('Y-m-d')     . ' 23:59:59';
        }


        if ($userId == 0) {
            $data = Sale::join('users as u', 'u.id', 'sales.user_id')
                ->select('sales.*', 'u.name as user')
                ->whereBetween('sales.created_at', [$from, $to])
                ->get();
        } else {
            $data = Sale::join('users as u', 'u.id', 'sales.user_id')
                ->select('sales.*', 'u.name as user')
                ->whereBetween('sales.created_at', [$from, $to])
                ->where('user_id', $userId)
                ->get();
        }

        $user = $userId == 0 ? 'Todos' : User::find($userId)->name;
        $pdf = PDF::loadView('pdf.reporte', compact('data', 'reportType', 'user', 'dateFrom', 'dateTo'));

        /*
    $pdf = new DOMPDF();
    $pdf->setBasePath(realpath(APPLICATION_PATH . '/css/'));
    $pdf->loadHtml($html);
    $pdf->render();
    */
        /*
    $pdf->set_protocol(WWW_ROOT);
    $pdf->set_base_path('/');
*/
        $user = Auth()->user()->name;
        $inspector = Inspectors::create([
            'user' => $user,
            'action' => 'Imprimio un reporte',
            'seccion' => 'Reportes'
        ]);
        return $pdf->stream('Ventas.pdf'); // visualizar
        //$customReportName = 'salesReport_'.Carbon::now()->format('Y-m-d').'.pdf';
        //return $pdf->download($customReportName); //descargar

    }


    public function reporteExcel($userId, $reportType, $dateFrom = null, $dateTo = null)
    {
        $reportName = 'Reporte de Ventas_' . uniqid() . '.xlsx';
        $user = Auth()->user()->name;
        $inspector = Inspectors::create([
            'user' => $user,
            'action' => 'Imprimio un reporte en excel',
            'seccion' => 'Reportes'
        ]);
        return Excel::download(new SalesExport($userId, $reportType, $dateFrom, $dateTo), $reportName);
    }
}
