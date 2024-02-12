<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Automattic\WooCommerce\Client;
use App\Models\Product;

class ProductoWooController extends Controller
{
    public function syncProducts(Request $request)
    {
        // Obtener los productos de tu CRM
        $products = Product::all();

        // Configuración de la API de WooCommerce
        $woocommerce = new Client(
            env('WOOCOMMERCE_STORE_URL'),
            env('WOOCOMMERCE_CONSUMER_KEY'),
            env('WOOCOMMERCE_CONSUMER_SECRET'),
            [
                'wp_api' => true,
                'version' => 'wc/v3',
            ]
        );

        // Variables para la barra de progreso
        $totalProducts = count($products);
        $successCount = 0;

        // Iterar sobre los productos y enviarlos a WooCommerce
        foreach ($products as $product) {
            $data = [
                'name' => $product->name,
                'sku' => $product->barcode,
                'regular_price' => $product->price,
                'stock_quantity' => $product->stock,
                
                // Agrega más campos según tus necesidades
            ];

            try {
                $response = $woocommerce->post('products', $data);

                if ($woocommerce->isSuccessful($response)) {
                    $successCount++;
                }
                dd($response);
            } catch (\Exception $e) {
                // Mostrar información sobre la excepción para depuración
                dd($e);
            }
        }

        return view('product.sync', [
            'totalProducts' => $totalProducts,
            'successCount' => $successCount,
        ]);
    }
}



