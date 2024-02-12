<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class WebhookProductController extends Controller
{
    public function woocommerceStockUpdate(Request $request)
    {
        try {
            // Obtener los datos enviados por WooCommerce
            $data = $request->all();
            $file = 'webhook-response.txt';
            $content = json_encode($data);
            file_put_contents($file, $content);
            // Registrar los datos del webhook en el archivo de registro
            Log::info('Datos del webhook de WooCommerce:', $data);
            $data = $request->all();
            $barcode = $data['sku'];
            $stockQuantity = $data['stock_quantity'];

            // Buscar el producto en base al SKU ($barcode)
            $product = Product::where('barcode', $barcode)->first();

            if ($product) {
                // Actualizar el stock del producto
                $product->stock = $stockQuantity;
                $product->save();
                // Puedes agregar aquí cualquier otra lógica adicional que desees ejecutar al actualizar el stock en tu CRM

                // Responder a WooCommerce con un código de estado 200 (OK) para indicar que la actualización se ha realizado correctamente
                return response()->json(['message' => 'Stock updated successfully'], 200);
            }

            // Responder a WooCommerce con un código de estado 200 (OK) para indicar que la solicitud se ha procesado correctamente
            return response()->json(['message' => 'Webhook received successfully'], 200);
        } catch (\Exception $e) {
            // Registrar el error en el archivo de registro
            Log::error('Error al procesar el webhook de WooCommerce: ' . $e->getMessage());

            // Responder con un código de estado 500 (Error interno del servidor) u otra respuesta adecuada según tus necesidades
            return response()->json(['message' => 'Error processing webhook'], 500);
        }
    }
}
