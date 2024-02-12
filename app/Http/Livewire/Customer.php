<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Illuminate\Support\Facades\Log;
use App\Mail\WelcomeEmail;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleDetail;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Traits\CartTrait;
use Automattic\WooCommerce\Client;

// Resto del código del archivo...


class ClientesController1 extends Component
{
    use WithFileUploads;
    use WithPagination;



    public $selected_id, $search;
    private $pagination = 5;
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

    public function info($id)
    {


        $data = Customer::find($id);

        $this->emit('modal-show', 'details loaded');
    }

    public function render()
    {

        $data = Customer::with('sale')->get();
        $data2 = Sale::with('client')->get();
        $data3 = SaleDetail::with('sales')->get();




        return view('livewire.clientes.clientes', ['data' => $data, 'data2' => $data2, 'data3' => $data3])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function Store()
    {
        try {
            // Verificar si el correo electrónico ya existe
            $existingClient = Customer::where('email', $this->email)->first();
            
            if ($existingClient) {
                // Si el correo ya existe, puedes manejar esto según tus necesidades.
                $this->emit('global-msg', 'El correo electrónico ya está registrado.');
                return;
            }
    
            // Crear un nuevo cliente
            $client = Customer::create([
                'name' => $this->name,
                'last_name' => $this->last_name,
                'last_name2' => $this->last_name2,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'address' => $this->address,
                'phone' => $this->phone,
                'saldo' => $this->saldo,
            ]);
    
            if ($this->image) {
                // Manejar la carga de la imagen
                $customFileName = uniqid() . '_.' . $this->image->extension();
                $this->image->storeAs('public/customers', $customFileName);
                $client->image = $customFileName;
                $client->save();
            }
    
            // Restablecer la interfaz de usuario y emitir eventos
            $this->resetUI();
            $this->emit('global-msg', 'NO SE MANDO NINGUN CORREO');
            $this->emit('cliente-added', 'Cliente Agregado');
        } catch (\Exception $e) {
            // Capturar excepciones y manejarlas según tus necesidades
            $this->emit('global-msg', 'Error al agregar el cliente: ' . $e->getMessage());
        }
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
        $this->phone = $record->phone;
        $this->saldo = $record->saldo;
        $this->image = null;

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
        ]);
        if ($this->image) {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/customers', $customFileName);
            $imageTemp = $clientee->image; // imagen temporal
            $clientee->image = $customFileName;
            $clientee->save();

            if ($imageTemp != null) {
                if (file_exists('storage/customers/' . $imageTemp)) {
                    unlink('storage/customers/' . $imageTemp);
                }
            }
        }
        try {
            Mail::to($this->email)->send(new WelcomeEmail($this->name, $this->email));
            // El correo se envió correctamente, muestra una notificación o un SweetAlert de éxito.

            $this->emit('global-msg', "EMAIL ENVIADO");
        } catch (\Exception $e) {
            $this->emit('global-msg', "EMAL NO ENVIADO");
            // Ocurrió un error al enviar el correo, muestra una notificación o un SweetAlert de error.

        }
        $this->resetUI();
        $this->emit('global-msg', "CLIENTE ACTUALIZADO");
        $this->emit('cliente-edit', 'Costumer Updated');
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
        $this->image = null;
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

        $this->emit('global-msg', "CLIENTE ELIMINADO");
        $this->resetUI();
        $this->emit('cliente-delete', 'Cliente Eliminado');
    }
}
