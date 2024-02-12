<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class WebhookController extends Controller
{
    protected $webhookSecret = 'oyarcegroup2023';

    public function handleWebhook(Request $request)
    {
        // Obtener el secreto enviado en el encabezado de la solicitud
        $webhookSecret = $request->header('X-WC-Webhook-Signature');

        // Comparar el secreto con tu valor personalizado
        if ($webhookSecret === $this->webhookSecret) {
            // Verificar si la solicitud del webhook contiene datos de cliente
            if ($request->has('customer')) {
                $customer = $request->input('customer');

                // Obtener detalles del cliente
                $customerId = $customer['id'];
                $customerName = $customer['first_name'] . ' ' . $customer['last_name'];
                $customerEmail = $customer['email'];

                // Verificar si el cliente ya existe en tu CRM
                $existingCustomer = Customer::where('id', $customerId)->first();

                if ($existingCustomer) {
                    // El cliente ya existe, realizar la actualización en tu CRM
                    $existingCustomer->name = $customerName;
                    $existingCustomer->email = $customerEmail;
                    // Actualizar los demás campos necesarios
                    $existingCustomer->save();

                    \Log::info("Cliente actualizado en el CRM: ID: $customerId, Nombre: $customerName, Email: $customerEmail");
                } else {
                    // El cliente no existe, crearlo en tu CRM
                    $newCustomer = new Customer();
                    $newCustomer->name = $customerName;
                    $newCustomer->email = $customerEmail;
                    // Establecer otros campos necesarios para el cliente
                    $newCustomer->id = $customerId;
                    // Guardar el nuevo cliente en tu CRM
                    $newCustomer->save();

                    \Log::info("Cliente creado en el CRM: ID: $customerId, Nombre: $customerName, Email: $customerEmail");
                }

                // Retornar una respuesta exitosa
                return response()->json(['status' => 'success']);
            }

            // Si no hay datos de cliente en la solicitud del webhook, retornar una respuesta de error
            return response()->json(['status' => 'error', 'message' => 'No se encontraron datos de cliente']);
        }

        // Si el secreto no coincide, retornar una respuesta de error
        return response()->json(['status' => 'error', 'message' => 'Secreto no válido']);
    }
}
