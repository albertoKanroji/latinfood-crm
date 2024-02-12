<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use App\Models\SaleDetail;

class UpdateSalesAPI extends Command
{
    protected $signature = 'sales:update-api';
    protected $description = 'Actualizar el estado de ventas y detalles a través de una API';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Obtener las ventas que cumplen con las condiciones
        $salesToUpdate = Sale::where('status', 'PAID')
                            ->where('status_envio', 'PENDIENTE')
                            ->get();

        foreach ($salesToUpdate as $sale) {
            // Obtener los detalles de la venta
            $saleDetails = SaleDetail::where('sale_id', $sale->id)->get();

            // Verificar si todos los detalles tienen scanned = 1
            $allDetailsScanned = $saleDetails->every(function ($detail) {
                return $detail->scanned == 1;
            });

            if ($allDetailsScanned) {
                // Actualizar el estado de envío de la venta
                $sale->update(['status_envio' => 'ACTUAL']);
                $this->info("Venta #{$sale->id} actualizada correctamente.");
            }
        }

        $this->info('Proceso completado.');

        return 0;
    }
}
