<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Mail\UpdateData;
use Automattic\WooCommerce\Client;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Inspectors;
use App\Models\SaleDetail;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Traits\CartTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;



class ClientesController extends Component
{

    use WithFileUploads;
    use WithPagination;
    public $selected_id, $search;
    private $pagination = 5;
    public $buscar = '';
    public $customerId;
    public $email, $pageTitle, $componentName, $sumDetails, $countDetails, $reportType, $userId, $saleId;
    public $name, $last_name, $last_name2, $phone, $address, $document, $password, $saldo, $image;
    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Clientes';

        $this->details = [];
        $this->sumDetails = 0;
        $this->countDetails = 0;
        $this->reportType = 0;
        $this->userId = 0;
        $this->saleId = 0;
    }
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function info($id)
    {


        $data = Customer::find($id);

        $this->emit('modal-show', 'details loaded');
    }

    public function render()
    {


        $data2 = Sale::with('client')->get();
        $data3 = SaleDetail::with('sales')->get();
        $data = Customer::with('sale')
            ->when($this->search, function ($query) {
                $query->where('name', $this->search);
            })
            ->get();



        return view('livewire.clientes.clientes', ['data' => $data, 'data2' => $data2, 'data3' => $data3])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function Store()
    {
        $user = Auth()->user()->name;
        $rules  = [
            'name' => 'required|min:3',
            'last_name' => 'required|min:3',

            'email' => 'required|unique:customers|min:10',
            'password' => 'required|min:1',
            'address' => 'required|min:8',
            'phone' => 'required|min:10',
            'saldo' => 'required'
        ];

        $messages = [
            'name.required' => 'Nombre del Cliente requerido',
            'name.min' => 'El nombre del Cliente debe tener al menos 3 caracteres',
            'last_name.required' => 'Apellido del Cliente requerido',
            'last_name.min' => 'El Apellido del Cliente debe tener al menos 3 caracteres',
            'email.required' => 'Email del Cliente requerido',
            'email.unique' => 'Ya existe este email asociado a una cuenta',
            'email.min' => 'El email del cliente debe tener al menos 10 caracteres',
            'password.required' => 'Contraseña del Cliente requerido',
            'password.min' => 'El Contraseña del Cliente debe tener al menos 8 caracteres',
            'address.required' => 'address del Cliente requerido',
            'address.min' => 'El address del Cliente debe tener al menos 8 caracteres',
            'phone.required' => 'phone del Cliente requerido',
            'phone.min' => 'El phone del Cliente debe tener al menos 10 caracteres',
            'saldo.required' => 'saldo del Cliente requerido',
        ];

        $this->validate($rules, $messages);
        $client = Customer::create([
            'name' => $this->name,
            'last_name' => $this->last_name,

            'email' => $this->email,
            'password' => bcrypt($this->password),
            'address' => $this->address,
            'phone' => $this->phone,
            'saldo' => $this->saldo,

        ]);
        if ($this->image) {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/customers', $customFileName);
            $client->image = $customFileName;
            $client->save();
        }

        Mail::to($this->email)->send(new WelcomeEmail(
            $this->name,
            $this->last_name,
            $this->last_name2,
            $this->phone,
            $this->address,
            $this->document,
            $this->password,
            $this->saldo,
            $this->email
        ));
        $woocommerce = new Client(
            'https://kdlatinfood.com', // URL de tu tienda WooCommerce
            'ck_8e38a879e7f6ce0d56e34c525de194a60c2e2ce5',
            'cs_723eab16e53f3607fd38984b00f763310cc4f473', // Secreto del consumidor de tu tienda WooCommerce
            [
                'wp_api' => true, // Habilitar la API de WordPress
                'version' => 'wc/v3', // Versión de la API de WooCommerce
            ]
        );

        $woocommerce->post('customers', [
            'email' => $this->email,
            'first_name' => $this->name,
            'last_name' => $this->last_name,
            // Otros campos del cliente en WooCommerce
        ]);
        //inspectors
        $inspector = Inspectors::create([
            'user' => $user,
            'action' => 'Creo al cliente: ' . $this->name,
            'seccion' => 'Customers'
        ]);
        $this->resetUI();
        $this->emit('global-msg', 'Cliente Agregado');
        $this->emit('cliente-added', 'Cliente Agregado');
    }
    public function getDetails($saleId)
    {
        $this->details = SaleDetail::join('products as p', 'p.id', 'sale_details.product_id')
            ->select('sale_details.id', 'sale_details.price', 'sale_details.quantity', 'p.name as product', 'p.barcode')
            ->where('sale_details.sale_id', $saleId)
            ->get();

        $suma = $this->details->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $this->sumDetails = $suma;
        $this->countDetails = $this->details->sum('quantity');
        $this->saleId = $saleId;

        $this->emit('show-modal', 'details loaded');
    }
    public function Edit($id)
    {
        $record = Customer::find($id);
        $this->name = $record->name;
        $this->last_name = $record->last_name;
        $this->last_name2 = $record->last_name2;
        $this->email = $record->email;
        $this->password = $record->password;
        $this->address = $record->address;
        $this->selected_id = $record->id;
        $this->phone = $record->phone;
        $this->saldo = $record->saldo;
        $this->image = null;

        // Check if the woocommerce_cliente_id is not null before making the API request
        if ($record->woocommerce_cliente_id !== null) {
            // Obtener el ID del cliente en WooCommerce utilizando el email
            $woocommerceClient = $this->getWooCommerceClient()->get('customers', ['email' => $record->email]);
            if (!empty($woocommerceClient)) {
                $this->customerId = $woocommerceClient[0]['id'];
            } else {
                // No se encontró un cliente en WooCommerce, puedes manejarlo como desees
            }
        } else {
            // The woocommerce_cliente_id is null, so skip the WooCommerce API request
            // You might want to set a default value for $this->customerId in this case.
            $this->customerId = null;
        }

        $this->emit('modal-show', 'show Modal');
    }


    public function Update()
    {
        $clientee = Customer::find($this->selected_id);
        $clientee->update([
            'name' => $this->name,
            'last_name' => $this->last_name,
            'last_name2' => $this->last_name2,
            'email' => $this->email,
            'password' => $this->password,
            'address' => $this->address,
            'phone' => $this->phone,
            'saldo' => $this->saldo,
        ]);
        if ($this->image) {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/customers', $customFileName);
            $clientee->image = $customFileName;
            $clientee->save();
        }

        // Actualizar los datos del cliente en WooCommerce
        if ($this->customerId) {
            $woocommerce = $this->getWooCommerceClient();
            $woocommerce->put("customers/{$this->customerId}", [
                'email' => $this->email,
                'first_name' => $this->name,
                'last_name' => $this->last_name,
                // Otros campos del cliente en WooCommerce
            ]);
        }

        Mail::to($this->email)->send(new UpdateData(
            $this->name,
            $this->last_name,
            $this->last_name2,
            $this->phone,
            $this->address,
            $this->document,
            $this->password,
            $this->saldo,
            $this->email
        ));

        $this->resetUI();
        $this->emit('global-msg', 'Cliente Editado');
        $this->emit('cliente-edit', 'Costumer Updated');
    }

    private function getWooCommerceClient()
    {
        return new Client(
            'https://kdlatinfood.com', // URL de tu tienda WooCommerce
            'ck_8e38a879e7f6ce0d56e34c525de194a60c2e2ce5',
            'cs_723eab16e53f3607fd38984b00f763310cc4f473', // Secreto del consumidor de tu tienda WooCommerce
            [
                'wp_api' => true, // Habilitar la API de WordPress
                'version' => 'wc/v3', // Versión de la API de WooCommerce
            ]
        );
    }

    public function resetUI()
    {
        $this->name = ' ';
        $this->last_name = ' ';
        $this->last_name2  = ' ';
        $this->email  = ' ';
        $this->password  = ' ';
        $this->address  = ' ';
        $this->phone = ' ';
        $this->saldo  = ' ';
        $this->search = '';
        $this->selected_id = 0;
    }
    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];
    public function Destroy(Customer $cliente)
    {
        if ($cliente->Sale()->exists()) {
            // El cliente tiene ventas asociadas, emitir evento para mostrar SweetAlert de error
            $this->emit('cliente-has-sales', 'El cliente tiene ventas asociadas y no puede ser eliminado.');
            return;
        }

        $imageTemp = $cliente->image;

        $cliente->delete();
        if ($imageTemp != null) {
            if (file_exists('storage/customers/' . $imageTemp)) {
                unlink('storage/customers/' . $imageTemp);
            }
        }
        $this->resetUI();
        $this->emit('global-msg', 'Cliente Eliminado');
        $this->emit('cliente-delete', 'Cliente Eliminado');
    }


    public function handleWebhook(Request $request)
    {
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


    public function createApi(Request $request)
    {
        try {
            $customer = new Customer();
            $customer->name = $request->input('name');
            $customer->last_name = $request->input('last_name');
            $customer->last_name2 = $request->input('last_name2');
            $customer->email = $request->input('email');
            $customer->password = Hash::make($request->input('password'));
            $customer->address = $request->input('address');
            $customer->phone = $request->input('phone');
            $customer->saldo = $request->input('saldo');
            // Otros campos del cliente
            $customer->save();

            return response()->json(['message' => 'Cliente creado con éxito'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear el cliente'], 500);
        }
    }
    public function editApiaAdress(Request $request, $id)
    {
        try {
            $customer = Customer::find($id);
            if (!$customer) {
                return response()->json(['message' => 'Cliente no encontrado'], 404);
            }

            $data = $request->only(['address']); // Solo permitir la actualización de 'address'

            // Actualizar el campo 'address'
            $customer->fill($data);
            $customer->save();

            return response()->json(['message' => 'Dirección del cliente actualizada con éxito'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar la dirección del cliente'], 500);
        }
    }


    public function editApi(Request $request, $id)
    {
        try {
            $customer = Customer::find($id);
            if (!$customer) {
                return response()->json(['message' => 'Cliente no encontrado'], 404);
            }

            $data = $request->only(['name', 'last_name', 'last_name2', 'phone', 'address', 'image']);

            // Actualizar los campos especificados
            $customer->fill($data);
            $customer->save();

            // Actualizar la imagen del cliente
            if ($request->hasFile('image')) {
                $customFileName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->storeAs('public/customers', $customFileName);
                $customer->image = $customFileName;
                $customer->save();
            }

            // Envío de correo al cliente
            Mail::to($customer->email)->send(new UpdateData(
                $customer->name,
                $customer->last_name,
                $customer->last_name2,
                $customer->phone,
                $customer->address,
                $customer->document,
                $customer->password,
                $customer->saldo,
                $customer->email
            ));

            return response()->json(['message' => 'Cliente actualizado con éxito'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar el cliente'], 500);
        }
    }



    public function getByIdApi($id)
    {
        try {
            $customer = Customer::with([
                'sale' => function ($query) {
                    $query->orderBy('status', 'desc'); // Ordenar por estado en orden descendente (PENDING primero)
                },
                'sale.salesDetails.product'
            ])->find($id);

            if (!$customer) {
                return response()->json(['message' => 'Cliente no encontrado'], 404);
            }

            return response()->json($customer, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener el cliente'], 500);
        }
    }


    public function FindUser($id)
    {
        try {
            $customer = Customer::find($id);
            if (!$customer) {
                return response()->json(['message' => 'Cliente no encontrado'], 404);
            }

            $customerData = [

                'name' => $customer->name,
                'last_name' => $customer->last_name,
                'last_name2' => $customer->last_name2,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'password' => $customer->password,
                'saldo' => $customer->saldo,
                'image' => asset('storage/customers/' . $customer->image),
                'woocommerce_cliente_id' => $customer->woocommerce_cliente_id,
            ];

            return response()->json($customerData, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener el cliente'], 500);
        }
    }


    public function loginApi(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('customer')->attempt($credentials)) {
            $customer = Auth::guard('customer')->user();
            $token = $customer->createToken('customer-token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
}
