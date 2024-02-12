<?php

namespace App\Http\Controllers;

use Livewire\Component;

use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Facades\Vendor;
use QuickBooksOnline\API\DataService\DataService;
use Illuminate\Http\Request;

class QuickBooksController extends Controller
{
    public function index()
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
        $customers = $dataService->Query("SELECT * FROM Customer");
        $invoices = $dataService->Query("SELECT * FROM Invoice");
        $payments = $dataService->Query("SELECT * FROM Payment");
        $vendors = $dataService->Query("SELECT * FROM Vendor");

        return view('livewire.quickbooks-view', compact('customers', 'invoices', 'payments', 'vendors'))->extends('layouts.theme.app')
            ->section('content');
    }
}
