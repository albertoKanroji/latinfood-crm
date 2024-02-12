<?php

namespace App\Http\Controllers;

use App\Mail\NewSale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class VentaWoocomerce extends Controller
{
   
    public function CrearVenta(Request $request)
    {
        try {
            // Obtener los datos del webhook
            $data = $request->all();

            // Verificar si el evento es una venta completada en WooCommerce
            if ($data['event'] === 'order.completed') {
                // Obtener la información relevante del pedido
                $order = $data['data']['order'];
                $orderId = $order['id'];
                $customerEmail = $order['billing']['email'];
                $total = $order['total'];
                $itemsQuantity = $order['line_items_count'];
                $efectivo = $order['payment_details']['paid_total'] ?? 0;
                $change = $order['payment_details']['change_total'] ?? 0;

                Log::info('Venta que llega del webhook EN VARIABLES PARA EL CRM:', [
                    'orderId' => $orderId,
                    'customerEmail' => $customerEmail,
                    'total' => $total,
                    'itemsQuantity' => $itemsQuantity,
                    'efectivo' => $efectivo,
                    'change' => $change,
                ]);


                // Buscar el cliente en tu CRM por su dirección de correo electrónico
                $customer = Customer::where('email', $customerEmail)->first();
                Log::info('Customer id:', $customer->id);
                if (!$customer) {
                    // El cliente no existe en la base de datos, crear uno nuevo
                    $customer = Customer::create([
                        'name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $customerEmail,
                        'phone' => $phone,
                        'address' => $address,
                        // Otros campos del modelo Customer
                    ]);

                    Log::info('Nuevo cliente creado:', [
                        'email' => $customerEmail,
                    ]);
                }

                // Crear la venta
                $sale = Sale::create([
                    'total' => $total,
                    'items' => $itemsQuantity,
                    'cash' => $efectivo,
                    'change' => $change,
                    'woocommerce_order_id' => $orderId,
                    'CustomerID' => $customer->id,
                ]);
                Log::info('ID DE VENTA DE WOOCOMERCE SE GUARDE EN EL CRM');
                Log::info('Venta Creada en el CRM:', $sale);

                if ($sale) {
                    $items = $order['line_items'];

                    foreach ($items as $item) {
                        $product = Product::where('barcode', $item['sku'])->first();

                        if (!$product) {
                            // Manejar el caso en el que el producto no exista en tu CRM
                            return response()->json(['message' => 'Producto no encontrado en el CRM'], 404);
                        }

                        $saledetail = SaleDetail::create([
                            'price' => $item['price'],
                            'quantity' => $item['quantity'],
                            'product_id' => $product->id,
                            'sale_id' => $sale->id,
                            'CustomerID' => $customer->id,
                        ]);
                        Log::info('Venta Detalle Creada en el CRM:', $saledetail);
                        $product->stock -= $item['quantity'];
                        $product->save();

                        $this->updateWooCommerceStock($item['sku'], $product->stock);
                    }
                }

                // Enviar correo electrónico al cliente
                $emailData = [
                    'customer' => $customer,
                    'sale' => $sale,
                    'items' => $items,
                ];
                Mail::to($customer->email)->send(new NewSale($emailData));
            }


            // Devolver una respuesta exitosa
            return response()->json(['message' => 'Venta registrada correctamente en el CRM'], 200);
        } catch (\Exception $e) {
            // Registrar el error en el archivo de registro o manejarlo según tus necesidades
            return response()->json(['message' => 'Error en el procesamiento del webhook'], 500);
        }
    }


    private function updateWooCommerceStock($barcode, $stock)
    {
        try {
            // Configurar la URL y los datos para la solicitud a la API de WooCommerce
            $url = 'https://kdlatinfood.com/wp-json/wc/v3/products';
            $productId = null;

            // Buscar el producto en WooCommerce por SKU ($barcode)
            $response = Http::withBasicAuth('ck_8e38a879e7f6ce0d56e34c525de194a60c2e2ce5', 'cs_723eab16e53f3607fd38984b00f763310cc4f473')
                ->get($url, ['sku' => $barcode]);

            if ($response->successful()) {
                $products = $response->json();
                if (!empty($products)) {
                    // Obtener el ID del producto en WooCommerce
                    $productId = $products[0]['id'];
                }
            }

            if ($productId) {
                // Actualizar el stock del producto en WooCommerce
                $response = Http::withBasicAuth('ck_8e38a879e7f6ce0d56e34c525de194a60c2e2ce5', 'cs_723eab16e53f3607fd38984b00f763310cc4f473')
                    ->put("$url/$productId", ['stock_quantity' => $stock]);

                if ($response->successful()) {
                    // El stock se actualizó correctamente en WooCommerce
                    // Puedes realizar alguna acción adicional si lo deseas
                    // Por ejemplo, registrar una entrada en los archivos de registro
                } else {
                    // Error al actualizar el stock en WooCommerce
                    // Puedes manejar el error según tus necesidades
                }
            } else {
                // No se encontró el producto en WooCommerce por SKU
                // Puedes manejar esta situación según tus necesidades
            }
        } catch (\Exception $e) {
            // Registrar el error en el archivo de registro o manejarlo según tus necesidades
        }
    }
    public function ActualizarVenta(Request $request)
    {
        try {
            // Obtener los datos enviados por WooCommerce
            $data = $request->all();
            $orderId = $data['id'];
            $customerEmail = $data['billing']['email'];
            $total = $data['total'];
            $lineItems = $data['line_items'];
            $itemsQuantity = count($data['line_items']);
            $efectivo = 0;  // No se proporciona en los datos del webhook
            $change = 0;
            // Buscar la venta existente por el ID de WooCommerce
            $venta = Sale::where('woocommerce_order_id', $orderId)->first();

            if ($venta) {
                // Ya existe una venta con el mismo ID de WooCommerce, no se crea un nuevo registro
                Log::info('Venta duplicada recibida del webhook:', [
                    'woocommerce_order_id' => $orderId,
                ]);
                return response()->json(['message' => 'Duplicate sale received'], 200);
            }

            Log::info('Venta Actualizada que llega del webhook EN VARIABLES PARA EL CRM:', [
                'orderId' => $orderId,
                'customerEmail' => $customerEmail,
                'total' => $total,
                'itemsQuantity' => $itemsQuantity,
                'efectivo' => $efectivo,
                'change' => $change,
            ]);
            // Buscar al cliente por su correo electrónico
            $customer = Customer::where('email', $customerEmail)->first();

            if (!$customer) {
                // El cliente no existe en la base de datos, crear uno nuevo
                $customer = Customer::create([
                    'name' => $data['billing']['first_name'] ?? '',
                    'last_name' => $data['billing']['last_name'] ?? '',
                    'email' => $customerEmail,
                    'phone' => $data['billing']['phone'] ?? '',
                    'address' => $data['billing']['address_1'] ?? '',
                    // Otros campos del modelo Customer
                ]);

                Log::info('Nuevo cliente creado:', [
                    'email' => $customerEmail,
                ]);
            }

            $customerId = $customer->id;

            // Registrar los datos del webhook en el archivo de registro
            Log::info('Datos del la venta webhook de WooCommerce:', $data);

            // Crear la venta en tu base de datos
            $venta = Sale::create([
                'total' => $total,
                'items' => $itemsQuantity,
                'change' => $change,
                'CustomerID' => $customerId,
                'woocommerce_order_id' => $orderId,
            ]);

            // Obtener los detalles de los productos vendidos
            $lineItems = $data['line_items'];
            $items = [];

            foreach ($lineItems as $item) {
                $price = $item['price'];
                $quantity = $item['quantity'];
                $sku = $item['sku'];

                // Buscar el producto por el SKU en el campo barcode
                $product = Product::where('barcode', $sku)->first();

                if ($product) {
                    $productId = $product->id;

                    // Crear el registro en SaleDetail
                    SaleDetail::create([
                        'price' => $price,
                        'quantity' => $quantity,
                        'product_id' => $productId,
                        'sale_id' => $venta->id, // Asignar el ID de la venta creada anteriormente
                    ]);
                    $product->stock -= $quantity;
                    $product->save();
                    Log::info('STOCK ACTTUALIZADO EN EL CRM');
                    $items[] = $productId;
                } else {
                    // El producto no fue encontrado en la base de datos, puedes manejarlo según tus requisitos
                    Log::warning('Producto no encontrado en la base de datos:', [
                        'sku' => $sku,
                    ]);
                }
            }

            Log::info('Venta creada en el CRM');
            // Enviar el correo electrónico al cliente
            /*   $emailData = [
            'customer' => $customer,
            'sale' => $venta,
            'items' => $items,
        ];

        Mail::to($customer->email)->send(new NewSale($emailData));

        Log::info('Email Enviado');*/
            // Responder a WooCommerce con un código de estado 200 (OK) para indicar que la solicitud se ha procesado correctamente
            return response()->json(['message' => 'Webhook deL CLIENTE ACTUALIZADO received successfully'], 200);
        } catch (\Exception $e) {
            // Registrar el error en el archivo de registro
            Log::error('Error al procesar el webhook de WooCommerce: ' . $e->getMessage());

            // Responder con un código de estado 500 (Error interno del servidor) u otra respuesta adecuada según tus necesidades
            return response()->json(['message' => 'Error processing webhook'], 500);
        }
    }
}
