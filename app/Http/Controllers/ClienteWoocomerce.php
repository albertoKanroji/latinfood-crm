<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Models\Customer;

class ClienteWoocomerce extends Controller
{
public function CrearCliente(Request $request)
{
    try {
        // Obtener los datos enviados por WooCommerce
        $data = $request->all();
Log::info('Cliente que llega del webhook:', $data);
        // Extraer los datos necesarios del webhook de WooCommerce
        $firstName = $data['first_name'];
        $lastName = $data['last_name'];
        $email = $data['email'];
        $phone = $data['billing']['phone'];
        $address = $data['billing']['address_1'];
        // Otras variables que necesites extraer...

        // Crear el cliente en tu base de datos
        $customer = Customer::create([
            'name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            // Otros campos del modelo Customer
        ]);
           Mail::to($email)->send(new WelcomeEmail(
            $firstName,
            $lastName,
            
            $phone,
            $address,
        
            $email
        ));

        // Registrar los datos del cliente en el archivo de registro
        Log::info('Cliente creado en la base de datos:', $customer->toArray());

        // Responder a WooCommerce con un código de estado 200 (OK) para indicar que la solicitud se ha procesado correctamente
        return response()->json(['message' => 'Cliente creado correctamente en la base de datos'], 200);
    } catch (\Exception $e) {
        // Registrar el error en el archivo de registro
        Log::error('Error al procesar el webhook de WooCommerce: ' . $e->getMessage());

        // Responder con un código de estado 500 (Error interno del servidor) u otra respuesta adecuada según tus necesidades
        return response()->json(['message' => 'Error processing webhook'], 500);
    }
}


public function ActualizarCliente(Request $request)
{
    try {
        // Obtener los datos enviados por WooCommerce
        $data = $request->all();

        // Registrar los datos del webhook en el archivo de registro
        Log::info('Datos del CLIENTE EDITADO webhook de WooCommerce:', $data);

        // Obtener los datos del cliente del webhook
        $customerId = $data['id'];
        $customerEmail = $data['email'];
        $firstName = $data['first_name'];
        $lastName = $data['last_name'];
        $address = $data['billing']['address_1'];
        $phone = $data['billing']['phone'];

        // Buscar al cliente por su correo electrónico
        $customer = Customer::where('email', $customerEmail)->first();

        if (!$customer) {
            // El cliente no existe en la base de datos, crear uno nuevo
            $customer = Customer::create([
                'name' => $firstName,
                'last_name' => $lastName,
                'email' => $customerEmail,
                'phone' => $phone,
                'address' => $address,
                'woocommerce_cliente_id'=>$customerId
                // Otros campos del modelo Customer
            ]);

            Log::info('Nuevo cliente creado:', [
                'email' => $customerEmail,
            ]);
        } else {
            // El cliente existe, actualizar sus datos
            $customer->name = $firstName;
            $customer->last_name = $lastName;
            $customer->email = $customerEmail;
            $customer->phone = $phone;
            $customer->address = $address;

            $customer->save();

            Log::info('Cliente actualizado:', [
                'email' => $customerEmail,
            ]);
        }

        // Responder a WooCommerce con un código de estado 200 (OK) para indicar que la solicitud se ha procesado correctamente
        return response()->json(['message' => 'Cliente actualizado en el CRM'], 200);
    } catch (\Exception $e) {
        // Registrar el error en el archivo de registro
        Log::error('Error al procesar el webhook de WooCommerce: ' . $e->getMessage());

        // Responder con un código de estado 500 (Error interno del servidor) u otra respuesta adecuada según tus necesidades
        return response()->json(['message' => 'Error processing webhook'], 500);
    }
}


}
