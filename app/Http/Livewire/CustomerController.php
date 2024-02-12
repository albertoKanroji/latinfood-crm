<?php

namespace App\Http\Livewire;

use QuickBooksOnline\API\DataService\DataService;
use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class CustomerController extends Component
{
    public $customers;

    public function mount()
    {
        $realmID = config('quickbooks.realmid');

        $url = config('quickbooks.baseurl') . "/v3/company/{$realmID}/query?query=select * from Customer";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('quickbooks.access_token'),
            'Accept' => 'application/json'
        ])->get($url);

        $this->customers = $response->json()['QueryResponse']['Customer'];
    }

    public function render()
    {
        return view('livewire.quickbooks-customers');
    }
}
