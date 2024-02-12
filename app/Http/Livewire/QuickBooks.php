<?php

namespace App\Http\Livewire;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Facades\Vendor;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2AccessToken;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use Illuminate\Http\Request;
use Livewire\Component;

class QuickBooks extends Component
{
    public function render(Request $request)
    {
         $dataService = DataService::Configure(array(
        'auth_mode' => 'oauth2',
        'ClientID' => env('QUICKBOOKS_CLIENT_ID'),
        'ClientSecret' => env('QUICKBOOKS_CLIENT_SECRET'),
        'accessTokenKey' => env('QUICKBOOKS_ACCESS_TOKEN'),
        'refreshTokenKey' => env('QUICKBOOKS_REFRESH_TOKEN'),
        'QBORealmID' => '4620816365288448880', // Reemplaza esto con tu Realm ID de QuickBooks
        'baseUrl' => "Development" // Opcional: Cambia a "Production" para acceder a la API en producción
    ));
         

    // Obtener datos de QuickBooks
     $query = "SELECT * FROM Customer";
        $customers = $dataService->Query($query);

        $query = "SELECT * FROM Invoice";
        $invoices = $dataService->Query($query);
 
        $query = "SELECT * FROM Payment";
        $payments = $dataService->Query($query);

        $query = "SELECT * FROM Vendor";
        $vendors = $dataService->Query($query);
    return view('livewire.quickbooks-view', compact('customers', 'invoices', 'payments', 'vendors'))->extends('layouts.theme.app')
            ->section('content');
    }
}
