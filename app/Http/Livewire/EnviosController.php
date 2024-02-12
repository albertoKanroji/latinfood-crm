<?php

namespace App\Http\Livewire;

//mails
use App\Mail\EnvioActual;
use App\Mail\EnvioFin;
use Illuminate\Support\Facades\Mail;
use Automattic\WooCommerce\Client;
use App\Models\Product;
use App\Models\Lotes;
use App\Models\Sale;
use App\Models\SaleDetail;
use Livewire\Component;
use App\Models\Envio;
use App\Models\Customer;
use App\Models\Operario;
use Illuminate\Http\Request;
use App\Models\Inspectors;
use Illuminate\Support\Facades\Cache;

class EnviosController extends Component
{
    public $selected_id, $venta, $pageTitle, $saleId, $detalle, $componentName, $countDetails;
    public $details;
    public $envio;

    public $saleDetails;



    public $selectedStatus;
    public function render()
    {
        $operario = Operario::all();
        $cliente = Customer::all();
        $lotes = Lotes::all();
        $sale = Sale::all();
        $data3 = Envio::with('operario')->get();
        $data = Envio::with('sales')->get();
        $data2 = Envio::with('transport')->get();
        return view('livewire.envios.envios', ['data' => $data, 'lotes' => $lotes, 'sale' => $sale, 'cliente' => $cliente, 'operario' => $operario, 'data2' => $data2, 'data3' => $data3, 'details' => $this->details])
            ->extends('layouts.theme.app')
            ->section('content');
    }
    public function mount()
    {
        $this->pageTitle = 'List';
        $this->componentName = 'Envios';
        $this->details = [];
        $this->sumDetails = 0;
        $this->countDetails = 0;
        $this->saleId = 0;
        $this->qrResult = 0;
        $this->detalle = 0;
    }


    ///mandar datos al QR




    public function QR($saleId)
    {
        $this->envio = Envio::where('id_sale', $saleId)->first();

        if ($this->envio) {
            $saleDetails = SaleDetail::where('sale_id', $this->envio->id_sale)->get();

            foreach ($saleDetails as $detail) {
                $lot = Lotes::find($detail->lot_id);
                if ($lot) {
                    $detail->codigoBarras = $lot->CodigoBarras;
                } else {
                    $detail->codigoBarras = 'No encontrado';
                }
            }

            $this->saleDetails = $saleDetails;
        }
        $this->emit('barcode-show', 'details loaded');
    }

    public function updateDetails($details)
    {
        $this->details = $details;
    }


    public function updateActual($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->status_envio = 'ACTUAL';
        $sale->save();

        // Envío del correo electrónico
        $cliente = Customer::findOrFail($sale->CustomerID);
        Mail::to($cliente->email)->send(new EnvioActual($sale));
        /*  $user = Auth()->user()->name;
        $inspector = Inspectors::create([
            'user' => $user,
            'action' => 'Se cambio el estado del pedido #' . $id. 'a ACTUAL',
            'seccion' => 'Envios'
        ]);*/
        return response()->json(['success' => true]);
        // Envío del correo electrónico

    }

    public function processQRCode($id, $keyProduct)
    {
        // Buscar la venta por su ID
        $sale = Sale::find($id);

        // Verificar si se encontró la venta
        if ($sale) {
            // Obtener los detalles de venta que coincidan con el product_id proporcionado
            $saleDetails = SaleDetail::where('sale_id', $sale->id)
                ->whereHas('product', function ($query) use ($keyProduct) {
                    $query->where('KeyProduct', $keyProduct);
                })
                ->first();

            // Verificar si se encontraron detalles de venta para el producto
            if ($saleDetails->isNotEmpty()) {
                // Devolver una respuesta de éxito con los detalles de venta del producto
                return response()->json(['saleDetails' => $saleDetails]);
            } else {
                // No se encontraron detalles de venta para el producto
                return response()->json(['message' => 'El producto no está en la venta.']);
            }
        } else {
            // No se encontró la venta

        }
    }

    public function BusquedaQRCode(Request $request, $qr, $ventaId)
    {
        // Buscar la venta por su ID
        $sale = Sale::find($ventaId);

        // Verificar si se encontró la venta
        if ($sale) {
            // Obtener los detalles de venta de la venta correspondiente al ventaId
            $saleDetails = SaleDetail::where('sale_id', $ventaId)->get();

            // Verificar si hay detalles de venta para la venta
            if ($saleDetails->isNotEmpty()) {
                $productMatch = false;
                $allScanned = true;

                foreach ($saleDetails as $saleDetail) {
                    if ($saleDetail->product->KeyProduct === $qr) {
                        if (!$saleDetail->scanned) {
                            // El código QR coincide con un KeyProduct y no ha sido escaneado previamente
                            $productMatch = true;
                            $saleDetail->scanned = true;
                            $saleDetail->save();
                        }
                    }
                    // Verificar si hay algún producto que no haya sido escaneado
                    if (!$saleDetail->scanned) {
                        $allScanned = false;
                    }
                }

                // Verificar si se encontró una coincidencia
                if ($productMatch) {
                    // Verificar si se han escaneado todos los productos
                    if ($allScanned) {
                        return response()->json(['message' => 'All Codebars are inserted']);
                    } else {
                        return response()->json(['message' => 'Pase al siguiente producto.']);
                    }
                } else {
                    // El código QR no coincide con ningún KeyProduct de los productos en la venta
                    return response()->json(['message' => '¡Código QR incorrecto!']);
                }
            } else {
                // No se encontraron detalles de venta para la venta
                return response()->json(['message' => 'No se encontraron detalles de venta para la venta.']);
            }
        } else {
            // No se encontró la venta
            return response()->json(['message' => 'No se encontró la venta.']);
        }
    }
    public function updateActualSales()
    {
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

            }
        }
    }
    public function updateSalesStatusAPI(Request $request)
{
    try {
        // Obtener las ventas que cumplen con las condiciones
        $salesToUpdate = Sale::where('status', 'PAID')
                            ->where('status_envio', 'PENDIENTE')
                            ->get();

        $updatedSales = []; // Almacena los IDs de las ventas actualizadas

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

                // Almacenar el ID de la venta actualizada
                $updatedSales[] = $sale->id;
            }
        }

        $response = [
            'success' => true,
            'message' => 'Proceso de actualización completado correctamente.',
            'updated_sales' => $updatedSales,
        ];

        return response()->json($response, 200);
    } catch (\Exception $e) {
        \Log::error($e);

        $response = [
            'success' => false,
            'message' => 'Hubo un error en el servidor.',
            'exception' => get_class($e),
        ];

        return response()->json($response, 500);
    }
}


    public function verifyQRCode(Request $request)
    {
        $ventaId = $request->input('ventaId');
        $qrCode = $request->input('qrCode');

        // Recuperar las claves de productos escaneadas para esta venta (si ya existen)
        $scannedProductKeys = Cache::get('scanned_product_keys_' . $ventaId, []);

        // Buscar la venta por su ID
        $sale = Sale::find($ventaId);

        if ($sale) {
            // Obtener los detalles de venta de la venta correspondiente al ventaId
            $saleDetails = SaleDetail::where('sale_id', $ventaId)->get();

            if ($saleDetails->isNotEmpty()) {
                $productMatch = false;

                foreach ($saleDetails as $saleDetail) {
                    if ($saleDetail->product->KeyProduct === $qrCode && !$saleDetail->scanned) {
                        // El código QR coincide con un KeyProduct y no ha sido escaneado previamente
                        $productMatch = true;
                        $saleDetail->scanned = true;
                        $saleDetail->save();

                        // Registrar la clave del producto escaneado
                        $scannedProductKeys[] = $qrCode;
                        Cache::put('scanned_product_keys_' . $ventaId, $scannedProductKeys, 60);

                        // Verificar si se han escaneado todas las claves de productos
                        $allScanned = $this->checkIfAllScanned($ventaId, $saleDetails);

                        if ($allScanned) {
                            // Llamar a la función updateActual si todas las claves de productos se han escaneado
                            $this->updateActual($ventaId);
                            // Borrar la caché cuando todos los códigos QR han sido escaneados
                            Cache::forget('scanned_product_keys_' . $ventaId);

                            return response()->json(['message' => 'Todos los codigos QR han sido escaneados.']);
                            $this->updateActualSales();
                        } else {
                            $this->updateActualSales();
                            return response()->json(['message' => 'Pase al siguiente producto.']);
                        }
                    }
                }

                if (!$productMatch) {
                    return response()->json(['message' => 'Codigo QR incorrecto para esta venta.']);
                    $this->updateActualSales();
                }
            } else {
                return response()->json(['message' => 'No se encontraron detalles de venta para la venta.']);
            }
        } else {
            return response()->json(['message' => 'No se encontro la venta.']);
        }
    }





    // Función para verificar si se han escaneado todas las claves de productos
    private function checkIfAllScanned($ventaId, $saleDetails)
    {
        $scannedProductKeys = Cache::get('scanned_product_keys_' . $ventaId, []);

        foreach ($saleDetails as $saleDetail) {
            if (!$saleDetail->scanned) {
                // Si hay algún producto no escaneado, retorna falso
                return false;
            }

            // Verificar si la clave del producto está registrada en las claves escaneadas
            if (!in_array($saleDetail->product->KeyProduct, $scannedProductKeys)) {
                // Si falta una clave en las escaneadas, retorna falso
                return false;
            }
        }

        // Si todas las claves de productos se han escaneado, retorna verdadero
        return true;
    }

    public function updateFinApi($id)
    {
        $sale = Sale::findOrFail($id);

        $sale->status_envio = 'FIN';
        $sale->save();

        $cliente = Customer::findOrFail($sale->CustomerID);
        Mail::to($cliente->email)->send(new EnvioFin($sale));


        return response()->json(['success' => true, 'message' => 'Order updated successfully']);
    }
    public function updateFin($id)
    {
        $sale = Sale::findOrFail($id);

        $sale->status_envio = 'FIN';
        $sale->save();

        $cliente = Customer::findOrFail($sale->CustomerID);
        Mail::to($cliente->email)->send(new EnvioFin($sale));

        // Check if the WooCommerce order ID is not null before making the API call
        /* if ($sale->woocommerce_order_id !== null) {
             $wooCommerceOrderId = $sale->woocommerce_order_id;
             $wooCommerceClient = new \Automattic\WooCommerce\Client(
                 'https://kdlatinfood.com', // URL de tu tienda WooCommerce
                 'ck_8e38a879e7f6ce0d56e34c525de194a60c2e2ce5',
                 'cs_723eab16e53f3607fd38984b00f763310cc4f473',
                 [
                     'wp_api' => true,
                     'version' => 'wc/v3',
                 ]
             );
             $wooCommerceClient->put('orders/' . $wooCommerceOrderId, ['status' => 'completed']);
         }*/

        return response()->json(['success' => true]);
    }


    public function guardarFirma(Request $request)
    {
        // Obtener la imagen de la firma del cuerpo de la solicitud
        $firma = $request->input('firma');

        // Generar un nombre único para la imagen
        $nombreImagen = 'firma_' . time() . '.png';

        // Almacenar la imagen en el almacenamiento temporal de Laravel
        $rutaTemporal = 'temp/' . $nombreImagen;
        Storage::put($rutaTemporal, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $firma)));

        // Obtener la URL de descarga de la imagen
        $urlDescarga = Storage::temporaryUrl($rutaTemporal, now()->addMinutes(5));
        $user = Auth()->user()->name;
        $inspector = Inspectors::create([
            'user' => $user,
            'action' => 'Se recibio la firma del cliente',
            'seccion' => 'Envios'
        ]);
        // Devolver la URL de descarga en la respuesta
        return response()->json(['url' => $urlDescarga]);
    }
}
