<?php

namespace App\Http\Livewire;

use Livewire\Component;

//Modelos
use App\Models\Product;
use App\Models\User;
use App\Models\Inspectors;
use App\Models\Insumo;
use App\Models\Sabores;
use App\Models\Category;
use App\Models\Lotes;

//Extras
use Carbon\Carbon;
use Illuminate\Support\Facades\Barcode;
use Illuminate\Support\Facades\Auth;

class InspectorsController extends Component
{
    public $componentName;
    public $selected_id;
    public $search;
    public $Sabor,$pageTitle;
    public function mount()
    {



        $this->pageTitle = 'Listado';
        $this->componentName = 'Lotes';
    }
    public function render()
    {
        $sabores = Sabores::orderBy('nombre')->get();
        $lotesAsociados = [];
        $inspectors = Inspectors::all();

        foreach ($sabores as $sabor) {
            $lotes = Lotes::where('sabor_id', $sabor->id)->get();
            $lotesAsociados[$sabor->id] = $lotes->groupBy('CodigoBarras');
        }

        $insumo = Lotes::where('CodigoBarras', $this->search)->orWhereDoesntHave('producto')->get();

        return view('livewire.inspectors.inspectors', [
            'sabor' => $sabores,
            'lotesAsociados' => $lotesAsociados,
            'insumo' => $insumo,
            'inspectors' => $inspectors,
        ])->extends('layouts.theme.app')->section('content');
    }
}
